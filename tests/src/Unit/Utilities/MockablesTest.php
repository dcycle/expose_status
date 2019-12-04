<?php

namespace Drupal\Tests\expose_status\Unit\Utilities;

use Drupal\expose_status\Utilities\Mockables;
use PHPUnit\Framework\TestCase;

// @codingStandardsIgnoreStart
class DummyMockablesObject {
  use Mockables;

}
// @codingStandardsIgnoreEnd

/**
 * Test Mockables.
 *
 * @group expose_status
 */
class MockablesTest extends TestCase {

  /**
   * Smoke test.
   */
  public function testSmoke() {
    $object = new DummyMockablesObject();

    $this->assertTrue(is_object($object));
  }

}
