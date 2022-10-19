<?php

namespace Drupal\expose_status;

/**
 * Abstraction around a collection of plugins.
 */
interface ExposeStatusPluginCollectionInterface {

  /**
   * Mockable wrapper around \Drupal::service('plugin.manager.expose_status').
   *
   * @return mixed
   *   The ExposeStatusPluginManager service. We are not specifying its type
   *   here because during testing we want to mock pluginManager() without
   *   extending ExposeStatusPluginManager; when we do, it works fine in
   *   PHPUnit directly. However when attempting to run within Drupal we
   *   get an unhelpful message as described in
   *   https://drupal.stackexchange.com/questions/252930. Therefore we simply
   *   use an anonymous class.
   *
   * @throws \Exception
   */
  public function pluginManager();

  /**
   * Get plugin objects.
   *
   * @param bool $reset
   *   Whether to re-fetch plugins; otherwise we use the static variable.
   *   This can be useful during testing.
   *
   * @return array
   *   Array of plugin objects.
   *
   * @throws \Exception
   */
  public function plugins(bool $reset = FALSE) : array;

  /**
   * Get plugin definitions based on their annotations.
   *
   * @return array
   *   Array of plugin definitions.
   *
   * @throws \Exception
   */
  public function pluginDefinitions() : array;

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
  public function exampleUrls(string $base_url, string $token) : array;

  /**
   * {@inheritdoc}
   */
  public function prepare();

}
