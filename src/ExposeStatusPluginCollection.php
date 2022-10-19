<?php

namespace Drupal\expose_status;

use Drupal\expose_status\Utilities\Singleton;
use Symfony\Component\HttpFoundation\Request;

/**
 * Abstraction around a collection of plugins.
 */
class ExposeStatusPluginCollection implements ExposeStatusPluginInterface, ExposeStatusPluginCollectionInterface {

  use Singleton;

  /**
   * {@inheritdoc}
   */
  public function pluginManager() {
    return \Drupal::service('plugin.manager.expose_status');
  }

  /**
   * {@inheritdoc}
   */
  public function plugins(bool $reset = FALSE) : array {
    static $return = NULL;

    if ($return === NULL || $reset) {
      $return = [];
      foreach (array_keys($this->pluginDefinitions()) as $plugin_id) {
        $return[$plugin_id] = $this->pluginManager()->createInstance($plugin_id, ['of' => 'configuration values']);
      }
    }

    return $return;
  }

  /**
   * {@inheritdoc}
   */
  public function pluginDefinitions() : array {
    $return = $this->pluginManager()->getDefinitions();

    uasort($return, function (array $a, array $b) : int {
      if ($a['weight'] == $b['weight']) {
          return 0;
      }
      return ($a['weight'] < $b['weight']) ? -1 : 1;
    });

    return $return;
  }

  /**
   * {@inheritdoc}
   */
  public function exampleUrls(string $base_url, string $token) : array {
    $return = [];

    foreach ($this->pluginDefinitions() as $pluginDefinition) {
      foreach ($pluginDefinition['examples'] as $example) {
        $return[] = str_replace('[url]', $base_url, str_replace('[token]', $token, $example));
      }
    }

    return $return;
  }

  /**
   * {@inheritdoc}
   */
  public function prepare() {
    foreach ($this->plugins() as $plugin) {
      $plugin->prepare();
    }
  }

  /**
   * {@inheritdoc}
   */
  public function alterResponse(Request $request, array $result, array &$response) {
    foreach ($this->plugins() as $plugin) {
      $plugin->alterResponse($request, $result, $response);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function alterResult(Request $request, array &$result) {
    foreach ($this->plugins() as $plugin) {
      $plugin->alterResult($request, $result);
    }
  }

}
