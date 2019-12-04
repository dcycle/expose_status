<?php

namespace Drupal\Tests\expose_status\Unit;

use Drupal\expose_status\ExposeStatusPluginManager;
use PHPUnit\Framework\TestCase;

/**
 * Test ExposeStatusPluginManager.
 *
 * @group expose_status
 */
class ExposeStatusPluginManagerTest extends TestCase {

  /**
   * Smoke test.
   */
  public function testSmoke() {
    $object = $this->getMockBuilder(ExposeStatusPluginManager::class)
      // NULL = no methods are mocked; otherwise list the methods here.
      ->setMethods(NULL)
      ->disableOriginalConstructor()
      ->getMock();

    $this->assertTrue(is_object($object));
  }

}
