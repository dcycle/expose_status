<?php

namespace Drupal\expose_status;

use Symfony\Component\HttpFoundation\Request;

/**
 * ExposeStatus singleton. Use \Drupal::service('expose_status').
 */
interface ExposeStatusInterface {

  /**
   * Mockable wrapper around global $base_url.
   */
  public function baseUrl() : string;

  /**
   * Get example URLs including examples from plugins.
   *
   * @param bool $obfuscate
   *   Whether or not to obfuscate the security token in the examples.
   *
   * @return array
   *   Array of example URLs for usage of this system.
   */
  public function exampleUrls(bool $obfuscate) : array;

  /**
   * Generate a random token.
   *
   * @return string
   *   A random token.
   */
  public function generateToken() : string;

  /**
   * Testable implementation of hook_requirements().
   */
  public function hookRequirements(string $phase) : array;

  /**
   * Get a string with instructions how to use this system, with examples.
   *
   * @param bool $obfuscate
   *   If TRUE, obfucase the token in the output.
   *
   * @return string
   *   Instructions on how to use this system.
   *
   * @throws \Exception
   */
  public function instructions(bool $obfuscate = FALSE) : string;

  /**
   * Get all ExposeStatusPlugin plugins.
   *
   * See the included expose_status_ignore module for an example of how to
   * create a Plugin.
   *
   * @return ExposeStatusPluginCollectionInterface
   *   All plugins.
   *
   * @throws \Exception
   */
  public function plugins() : ExposeStatusPluginCollectionInterface;

  /**
   * Get the raw data from Drupal.
   *
   * @return array
   *   The raw data of Drupal requirements.
   *
   * @throws \Exception
   */
  public function rawData() : array;

  /**
   * Given raw data, return either "ok", or "issues found; please check".
   *
   * Developers can override this using the plugin system.
   *
   * @param array $raw
   *   The raw data.
   *
   * @return string
   *   "ok", or "issues found; please check".
   */
  public function rawDataToStatus(array $raw) : string;

  /**
   * Get a result based on a request object.
   *
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   The Request object.
   *
   * @return array
   *   An array with the keys cache, unaltered_raw, raw, and response.
   *
   * @throws \Exception
   */
  public function result(Request $request) : array;

  /**
   * Get the token, creating it if it does not exist.
   *
   * @param bool $obfuscate
   *   If TRUE, return an obfuscated (*****) version of the token.
   * @param bool $reset
   *   Whether or not reset the token.
   *
   * @return string
   *   The existing or newly-created token, or an obfuscated version thereof.
   */
  public function token(bool $obfuscate = FALSE, bool $reset = FALSE) : string;

}
