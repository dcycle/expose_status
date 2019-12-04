<?php

namespace Drupal\Tests\expose_status\Unit;

use Drupal\expose_status\ExposeStatusPluginManager;
use Drupal\expose_status\ExposeStatusPluginCollection;
use PHPUnit\Framework\TestCase;

/**
 * Test ExposeStatusPluginCollection.
 *
 * @group expose_status
 */
class ExposeStatusPluginCollectionTest extends TestCase {

  /**
   * Test for pluginDefinitions().
   *
   * @param string $message
   *   The test message.
   * @param array $input
   *   The mock definitions.
   * @param array $expected
   *   The expected output.
   *
   * @cover ::pluginDefinitions
   * @dataProvider providerPluginDefinitions
   */
  public function testPluginDefinitions(string $message, array $input, array $expected) {
    $object = $this->getMockBuilder(ExposeStatusPluginCollection::class)
      // NULL = no methods are mocked; otherwise list the methods here.
      ->setMethods([
        'pluginManager',
      ])
      ->disableOriginalConstructor()
      ->getMock();

    // @codingStandardsIgnoreStart
    $object->method('pluginManager')
      ->willReturn(new class($input) extends ExposeStatusPluginManager {
        public function __construct($input) {
          $this->input = $input;
        }
        public function getDefinitions() {
          return $this->input;
        }
      });

    $output = $object->pluginDefinitions();

    if ($output != $expected) {
      print_r([
        'message' => $message,
        'output' => $output,
        'expected' => $expected,
      ]);
    }

    $this->assertTrue($output == $expected, $message);
    // @codingStandardsIgnoreEnd
  }

  /**
   * Provider for testPluginDefinitions().
   */
  public function providerPluginDefinitions() {
    return [
      [
        'message' => 'Already sorted',
        'input' => [
          'a' => [
            'weight' => 1,
          ],
          'b' => [
            'weight' => 2,
          ],
        ],
        'expected' => [
          'a' => [
            'weight' => 1,
          ],
          'b' => [
            'weight' => 2,
          ],
        ],
      ],
      [
        'message' => 'Not sorted',
        'input' => [
          'a' => [
            'weight' => 3,
          ],
          'b' => [
            'weight' => 2,
          ],
        ],
        'expected' => [
          'b' => [
            'weight' => 2,
          ],
          'a' => [
            'weight' => 3,
          ],
        ],
      ],
    ];
  }

  /**
   * Get plugin definitions based on their annotations.
   *
   * @return array
   *   Array of plugin definitions.
   *
   * @throws \Exception
   */
  public function pluginDefinitions() : array {
    $return = $this->pluginManager()->getDefinitions();

    uasort($return, function (array $a, array $b) : int {
      if ($a['weight'] == $b['weight']) {
          return 0;
      }
      return ($a['weight'] < $b['weight']) ? -1 : 1;
    });

    return $return;
  }

}
