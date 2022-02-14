<?php

namespace Drupal\expose_status_ignore\Plugin\ExposeStatusPlugin;

use Drupal\expose_status\ExposeStatusPluginBase;
use Symfony\Component\HttpFoundation\Request;

/**
 * Allows you to ignore certain lines in the requirements.
 *
 * For example, if you don't care about the PHP Opcode Caching and
 * Webform: Private Files warnings for the purpose of monitoring your
 * application (perhaps you are working on these and only want to notified in
 * case _other_ issues arise), you can ignore these two issues:
 *
 * (1) run
 *
 * drush ev "print_r(\Drupal::service('system.manager')->listRequirements());"
 *
 * in the command line,
 *
 * (2) identify the keys of the issues you want to ignore, in this case
 * php_opcache and webform_file_private, then append the get parameter:
 *
 * ?ignore=php_opcache,webform_file_private to the request URL.
 *
 * @ExposeStatusPluginAnnotation(
 *   id = "expose_status_plugin_ignore",
 *   description = @Translation("Provide the labels of any status lines you want to ignore."),
 *   examples = {
 *     "[url]/admin/reports/status/expose/[token]?ignore=php_opcache,webform_file_private",
 *   },
 *   weight = 0,
 * )
 */
class IgnoreLines extends ExposeStatusPluginBase {

  /**
   * {@inheritdoc}
   */
  public function alterResult(Request $request, array &$result) {
    // According to
    // https://github.com/symfony/http-foundation/blob/5.4/Request.php
    // query is valid.
    // @phpstan-ignore-next-line
    $query = $request->query;
    foreach (explode(',', $query->get('ignore')) as $key) {
      unset($result['raw'][$key]);
    }
  }

}
