<?php

namespace Drupal\Tests\expose_status_ignore\Unit\Plugin\ExposeStatusPlugin;

use Drupal\expose_status_ignore\Plugin\ExposeStatusPlugin\IgnoreLines;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

/**
 * Test IgnoreLines.
 *
 * @group expose_status
 */
class IgnoreLinesTest extends TestCase {

  /**
   * Test for alterResult().
   *
   * @param string $message
   *   The test message.
   * @param array $result
   *   The mock value of result to be altered.
   * @param string $get
   *   The mock value of the "ignore" $_GET parameter.
   * @param array $expected
   *   The expected altered version of $result.
   *
   * @cover ::alterResult
   * @dataProvider providerAlterResult
   */
  public function testAlterResult(string $message, array $result, string $get, array $expected) {
    $object = $this->getMockBuilder(IgnoreLines::class)
      // NULL = no methods are mocked; otherwise list the methods here.
      ->setMethods(NULL)
      ->disableOriginalConstructor()
      ->getMock();

    $output = $result;
    // @codingStandardsIgnoreStart
    $object->alterResult(new class($get) extends Request {
      public function __construct($get) {
        $this->query = new class($get) {
          public function __construct($get) {
            $this->get = $get;
          }
          public function get($x) : string {
            if ($x == 'ignore') {
              return $this->get;
            }
            return '';
          }
        };
      }
    }, $output);
    // @codingStandardsIgnoreEnd

    if ($output != $expected) {
      print_r([
        'output' => $output,
        'expected' => $expected,
      ]);
    }

    $this->assertTrue($output == $expected, $message);
  }

  /**
   * Provider for testAlterResult().
   */
  public function providerAlterResult() {
    return [
      [
        'message' => 'basic case, no altering',
        'result' => [
          'raw' => [
            'hello' => 'whatever',
            'world' => 'whatever',
          ],
        ],
        'get' => '',
        'expected' => [
          'raw' => [
            'hello' => 'whatever',
            'world' => 'whatever',
          ],
        ],
      ],
      [
        'message' => 'remove non-exisitant',
        'result' => [
          'raw' => [
            'hello' => 'whatever',
            'world' => 'whatever',
          ],
        ],
        'get' => 'key does not exist',
        'expected' => [
          'raw' => [
            'hello' => 'whatever',
            'world' => 'whatever',
          ],
        ],
      ],
      [
        'message' => 'remove hello',
        'result' => [
          'raw' => [
            'hello' => 'whatever',
            'world' => 'whatever',
          ],
        ],
        'get' => 'hello',
        'expected' => [
          'raw' => [
            'world' => 'whatever',
          ],
        ],
      ],
      [
        'message' => 'remove hello and non-existant',
        'result' => [
          'raw' => [
            'hello' => 'whatever',
            'world' => 'whatever',
          ],
        ],
        'get' => 'hello,some other key',
        'expected' => [
          'raw' => [
            'world' => 'whatever',
          ],
        ],
      ],
      [
        'message' => 'remove everything',
        'result' => [
          'raw' => [
            'hello' => 'whatever',
            'world' => 'whatever',
          ],
        ],
        'get' => 'hello,world',
        'expected' => [
          'raw' => [],
        ],
      ],
    ];
  }

}
