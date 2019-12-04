<?php

namespace Drupal\Tests\expose_status\Unit;

use Drupal\expose_status\ExposeStatusPluginBase;
use PHPUnit\Framework\TestCase;

/**
 * Test ExposeStatusPluginBase.
 *
 * @group expose_status
 */
class ExposeStatusPluginBaseTest extends TestCase {

  /**
   * Smoke test.
   */
  public function testSmoke() {
    $object = $this->getMockBuilder(ExposeStatusPluginBase::class)
      // NULL = no methods are mocked; otherwise list the methods here.
      ->setMethods(NULL)
      ->disableOriginalConstructor()
      ->getMock();

    $this->assertTrue(is_object($object));
  }

}
