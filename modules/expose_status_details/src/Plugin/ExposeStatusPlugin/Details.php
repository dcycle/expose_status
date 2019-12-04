<?php

namespace Drupal\expose_status_details\Plugin\ExposeStatusPlugin;

use Drupal\expose_status\ExposeStatusPluginBase;
use Symfony\Component\HttpFoundation\Request;

/**
 * Adds details.
 *
 * @ExposeStatusPluginAnnotation(
 *   id = "expose_status_plugin_details",
 *   description = @Translation("Adds details to the result."),
 *   weight = 100,
 *   examples = {
 *   },
 * )
 */
class Details extends ExposeStatusPluginBase {

  /**
   * {@inheritdoc}
   */
  public function alterResponse(Request $request, array $result, array &$response) {
    $response['details'] = [];
    foreach ($result['raw'] as $id => $line) {
      $response['details'][$id] = isset($line['severity']) ? $line['severity'] : 0;
    }
  }

}
