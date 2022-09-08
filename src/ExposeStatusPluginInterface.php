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
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   The request object.
   * @param array $result
   *   The original result information.
   * @param array $response
   *   The response to alter.
   */
  public function alterResponse(Request $request, array $result, array &$response);

  /**
   * Prepare the system before fetching raw data.
   *
   * A real-world example of this is
   * ./src/Plugin/ExposeStatusPlugin/PrepUpdates.php which loads upadte
   * information to make it up-to-date.
   */
  public function prepare();

  /**
   * Alter result.
   *
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   The request object.
   * @param array $result
   *   The result to alter.
   */
  public function alterResult(Request $request, array &$result);

}
