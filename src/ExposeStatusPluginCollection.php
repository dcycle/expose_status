<?php

namespace Drupal\expose_status;

use Drupal\expose_status\Utilities\Singleton;
use Symfony\Component\HttpFoundation\Request;

/**
 * Abstraction around a collection of plugins.
 */
class ExposeStatusPluginCollection implements ExposeStatusPluginInterface {

  use Singleton;

  /**
   * Mockable wrapper around \Drupal::service('plugin.manager.expose_status').
   *
   * @return ExposeStatusPluginManager
   *   The ExposeStatusPluginManager service.
   *
   * @throws \Exception
   */
  public function pluginManager() : ExposeStatusPluginManager {
    return \Drupal::service('plugin.manager.expose_status');
  }

  /**
   * Get plugin objects.
   *
   * @return array
   *   Array of plugin objects.
   *
   * @throws \Exception
   */
  public function plugins() : array {
    static $return = NULL;

    if ($return === NULL) {
      foreach (array_keys($this->pluginDefinitions()) as $plugin_id) {
        $return[$plugin_id] = $this->pluginManager()->createInstance($plugin_id, ['of' => 'configuration values']);
      }
    }

    return $return;
  }

  /**
   * Get plugin definitions based on their annotations.
   *
   * @return array
   *   Array of plugin definitions.
   *
   * @throws \Exception
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
   * Get an array of example URLs for usage.
   *
   * @param string $base_url
   *   The base URL to use for the examples.
   * @param string $token
   *   A token which should be used for the examples.
   *
   * @return array
   *   Array of example URLs for usage.
   *
   * @throws \Exception
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
