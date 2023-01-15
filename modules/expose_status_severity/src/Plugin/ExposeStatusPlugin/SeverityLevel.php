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
   * Get the security level above which to trigger an error.
   *
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   The request object.
   *
   * @return int
   *   The security level above which to trigger an error.
   */
  public function onlyAboveLevel(Request $request) : int {
    // Starting in version 4.1.0 we are using only_above_level which is less
    // confusing that level. But wee might be dealing with sites that still use
    // the pre-4.1.0 "level".
    foreach (['only_above_level', 'level'] as $param) {
      $candidate = intval($request->query->get($param));
      if ($candidate) {
        return $candidate;
      }
    }
    return 0;
  }

  /**
   * {@inheritdoc}
   */
  public function alterResponse(Request $request, array $result, array &$response) {
    if ($level = $this->onlyAboveLevel($request)) {
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
