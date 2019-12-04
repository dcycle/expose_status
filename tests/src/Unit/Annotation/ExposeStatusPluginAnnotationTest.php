<?php

namespace Drupal\Tests\expose_status\Unit\Annotation;

use Drupal\expose_status\Annotation\ExposeStatusPluginAnnotation;
use PHPUnit\Framework\TestCase;

/**
 * Test ExposeStatusPluginAnnotation.
 *
 * @group expose_status
 */
class ExposeStatusPluginAnnotationTest extends TestCase {

  /**
   * Smoke test.
   */
  public function testSmoke() {
    $object = $this->getMockBuilder(ExposeStatusPluginAnnotation::class)
      // NULL = no methods are mocked; otherwise list the methods here.
      ->setMethods(NULL)
      ->disableOriginalConstructor()
      ->getMock();

    $this->assertTrue(is_object($object));
  }

}
