<?php

namespace Drupal\expose_status\Plugin\ExposeStatusPlugin;

use Drupal\expose_status\ExposeStatusPluginBase;
use Symfony\Component\HttpFoundation\Request;

/**
 * Prepares the system by making sure update info is up-to-date.
 *
 * @ExposeStatusPluginAnnotation(
 *   id = "expose_status_plugin_update_info",
 *   description = @Translation("Prepares the system by making sure update info is up-to-date."),
 *   weight = 100,
 *   examples = {
 *   },
 * )
 */
class Details extends ExposeStatusPluginBase {

  /**
   * {@inheritdoc}
   */
  public function prepare(Request $request) {
    if (function_exists('update_get_available')) {
      // This makes the update info up-to-date so that if you update a module,
      // that fact will be reflected immediately.
      update_get_available(TRUE);
    }
  }

}
