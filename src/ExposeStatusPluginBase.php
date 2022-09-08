<?php

namespace Drupal\expose_status;

use Drupal\Component\Plugin\PluginBase;
use Symfony\Component\HttpFoundation\Request;

/**
 * A base class to help developers implement ExposeStatusPlugin objects.
 *
 * @see \Drupal\expose_status\Annotation\ExposeStatusPluginAnnotation
 * @see \Drupal\expose_status\ExposeStatusPluginInterface
 */
abstract class ExposeStatusPluginBase extends PluginBase implements ExposeStatusPluginInterface {

  /**
   * {@inheritdoc}
   */
  public function prepare(Request $request) {
    // Do nothing. Subclasses can prepare if they need to.
  }

  /**
   * {@inheritdoc}
   */
  public function alterResponse(Request $request, array $result, array &$response) {
    // Do nothing. Subclasses can alter the response if they need to.
  }

  /**
   * {@inheritdoc}
   */
  public function alterResult(Request $request, array &$result) {
    // Do nothing. Subclasses can alter the result if they need to.
  }

}
