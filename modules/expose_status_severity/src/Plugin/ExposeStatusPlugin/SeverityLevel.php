<?php

namespace Drupal\expose_status_severity\Plugin\ExposeStatusPlugin;

use Drupal\expose_status\ExposeStatusPluginBase;
use Symfony\Component\HttpFoundation\Request;

/**
 * Allows users to filter by severity level.
 *
 * @ExposeStatusPluginAnnotation(
 *   id = "expose_status_plugin_severity",
 *   description = @Translation("Allows users to filter by severity level."),
 *   weight = 1,
 *   examples = {
 *     "[url]/admin/reports/status/expose/[token]?severity=1",
 *   },
 * )
 */
class SeverityLevel extends ExposeStatusPluginBase {

  /**
   * {@inheritdoc}
   */
  public function alterResponse(Request $request, array $result, array &$response) {
    $query = $request->query;
    if ($level = intval($query->get('level'))) {
      $response['status'] = 'ok';
      foreach ($result['raw'] as $line) {
        if (isset($line['severity']) && $line['severity'] > $level) {
          $response['status'] = 'issues found; please check';
          break;
        }
      }
    }
  }

}
