<?php

namespace Drupal\expose_status\Utilities;

use Drupal\Core\Access\AccessResult;
// @codingStandardsIgnoreStart
use Drupal\expose_status\ExposeStatus;
// @codingStandardsIgnoreEnd

/**
 * A quick way to fetch mockable service singletons.
 */
trait Mockables {

  /**
   * Mockable wrapper around AccessResult::allowed().
   */
  public function accessAllowed() {
    return AccessResult::allowed();
  }

  /**
   * Mockable wrapper around AccessResult::forbidden().
   */
  public function accessForbidden() {
    return AccessResult::forbidden();
  }

  /**
   * Mockable wrapper around \Drupal::service('expose_status').
   */
  public function exposeStatusService() : ExposeStatus {
    return \Drupal::service('expose_status');
  }

}
