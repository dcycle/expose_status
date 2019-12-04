<?php

namespace Drupal\Tests\expose_status\Unit\Utilities;

use Drupal\expose_status\Utilities\Singleton;
use PHPUnit\Framework\TestCase;

// @codingStandardsIgnoreStart
class DummySingletonObject {
  use Singleton;

}
// @codingStandardsIgnoreEnd

/**
 * Test Singleton.
 *
 * @group expose_status
 */
class SingletonTest extends TestCase {

  /**
   * Smoke test.
   */
  public function testSmoke() {
    $this->assertTrue(DummySingletonObject::instance() === DummySingletonObject::instance());
  }

}
