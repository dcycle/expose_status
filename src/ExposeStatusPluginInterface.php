<?php

namespace Drupal\expose_status;

use Symfony\Component\HttpFoundation\Request;

/**
 * An interface for all Sandwich type plugins.
 *
 * When defining a new plugin type you need to define an interface that all
 * plugins of the new type will implement. This ensures that consumers of the
 * plugin type have a consistent way of accessing the plugin's functionality. It
 * should include access to any public properties, and methods for accomplishing
 * whatever business logic anyone accessing the plugin might want to use.
 *
 * For example, an image manipulation plugin might have a "process" method that
 * takes a known input, probably an image file, and returns the processed
 * version of the file.
 *
 * In our case we'll define methods for accessing the human readable description
 * of a sandwich and the number of calories per serving. As well as a method for
 * ordering a sandwich.
 */
interface ExposeStatusPluginInterface {

  /**
   * Alter response.
   *
   * @param Request $request
   *   The request object.
   * @param array $result
   *   The original result information.
   * @param array $response
   *   The response to alter.
   */
  public function alterResponse(Request $request, array $result, array &$response);

  /**
   * Alter result.
   *
   * @param Request $request
   *   The request object.
   * @param array $result
   *   The result to alter.
   */
  public function alterResult(Request $request, array &$result);

}
