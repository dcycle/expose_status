<?php

namespace Drupal\Tests\expose_status\Unit;

use Drupal\expose_status\ExposeStatus;
use PHPUnit\Framework\TestCase;

/**
 * Test ExposeStatus.
 *
 * @group expose_status
 */
class ExposeStatusTest extends TestCase {

  /**
   * Smoke test.
   */
  public function testSmoke() {
    $object = $this->getMockBuilder(ExposeStatus::class)
      // NULL = no methods are mocked; otherwise list the methods here.
      ->setMethods(NULL)
      ->disableOriginalConstructor()
      ->getMock();

    $this->assertTrue(is_object($object));
  }

}
