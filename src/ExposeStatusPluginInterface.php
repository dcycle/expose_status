<?php

namespace Drupal\expose_status;

use Symfony\Component\HttpFoundation\Request;

/**
 * An interface for all ExposeStatusPlugin type plugins.
 *
 * This is based on code from the Examples module.
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
