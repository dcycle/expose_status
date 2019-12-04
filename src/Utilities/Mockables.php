<?php

namespace Drupal\expose_status\Utilities;

// @codingStandardsIgnoreStart
use Drupal\expose_status\ExposeStatus;
// @codingStandardsIgnoreEnd

/**
 * A quick way to fetch mockable service singletons.
 */
trait Mockables {

  /**
   * Mockable wrapper around \Drupal::service('expose_status').
   */
  public function exposeStatusService() : ExposeStatus {
    return \Drupal::service('expose_status');
  }

}
