<?php

namespace Drupal\expose_status_details\Plugin\ExposeStatusPlugin;

use Drupal\expose_status\ExposeStatusPluginBase;
use Symfony\Component\HttpFoundation\Request;

/**
 * Adds details from the status report (rather than just "ok" or "issues found".
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
      $response['details'][$id] = $line['severity'] ?? 0;
    }
  }

}
