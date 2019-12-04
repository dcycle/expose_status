<?php

namespace Drupal\Tests\expose_status_severity\Unit\Plugin\ExposeStatusPlugin;

use Drupal\expose_status_severity\Plugin\ExposeStatusPlugin\SeverityLevel;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

/**
 * Test SeverityLevel.
 *
 * @group expose_status
 */
class SeverityLevelTest extends TestCase {

  /**
   * Test for alterResponse().
   *
   * @param string $message
   *   The test message.
   * @param array $result
   *   The mock value of result.
   * @param array $response
   *   The mock value of response to be altered.
   * @param string $get
   *   The mock value of the "level" $_GET parameter.
   * @param array $expected
   *   The expected altered version of $result.
   *
   * @cover ::alterResponse
   * @dataProvider providerAlterResponse
   */
  public function testAlterResponse(string $message, array $result, array $response, string $get, array $expected) {
    $object = $this->getMockBuilder(SeverityLevel::class)
      // NULL = no methods are mocked; otherwise list the methods here.
      ->setMethods(NULL)
      ->disableOriginalConstructor()
      ->getMock();

    $output = $response;
    // @codingStandardsIgnoreStart
    $object->alterResponse(new class($get) extends Request {
      public function __construct($get) {
        $this->query = new class($get) {
          public function __construct($get) {
            $this->get = $get;
          }
          public function get($x) : string {
            if ($x == 'level') {
              return $this->get;
            }
            return '';
          }
        };
      }
    }, $result, $output);
    // @codingStandardsIgnoreEnd

    if ($output != $expected) {
      print_r([
        'message' => $message,
        'output' => $output,
        'expected' => $expected,
      ]);
    }

    $this->assertTrue($output == $expected, $message);
  }

  /**
   * Provider for testAlterResponse().
   */
  public function providerAlterResponse() {
    return [
      [
        'message' => 'basic case, no altering',
        'result' => [
          'raw' => [
            'hello' => [
              'severity' => 10,
            ],
            'world' => [
              'severity' => 11,
            ],
          ],
        ],
        'response' => [
          'status' => 'anything',
        ],
        'get' => '',
        'expected' => [
          'status' => 'anything',
        ],
      ],
      [
        'message' => 'altering does not apply',
        'result' => [
          'raw' => [
            'hello' => [
              'severity' => 10,
            ],
            'world' => [
              'severity' => 15,
            ],
          ],
        ],
        'response' => [
          'status' => 'anything',
        ],
        'get' => '400',
        'expected' => [
          'status' => 'ok',
        ],
      ],
      [
        'message' => 'altering applies to one',
        'result' => [
          'raw' => [
            'hello' => [
              'severity' => 10,
            ],
            'world' => [
              'severity' => 15,
            ],
          ],
        ],
        'response' => [
          'status' => 'anything',
        ],
        'get' => '12',
        'expected' => [
          'status' => 'issues found; please check',
        ],
      ],
      [
        'message' => 'altering applies to all',
        'result' => [
          'raw' => [
            'hello' => [
              'severity' => 10,
            ],
            'world' => [
              'severity' => 15,
            ],
          ],
        ],
        'response' => [
          'status' => 'anything',
        ],
        'get' => '-12',
        'expected' => [
          'status' => 'issues found; please check',
        ],
      ],
      [
        'message' => 'fload',
        'result' => [
          'raw' => [
            'hello' => [
              'severity' => 10,
            ],
            'world' => [
              'severity' => 15,
            ],
          ],
        ],
        'response' => [
          'status' => 'anything',
        ],
        'get' => '-12.60',
        'expected' => [
          'status' => 'issues found; please check',
        ],
      ],
      [
        'message' => 'undecipherable get paramter',
        'result' => [
          'raw' => [
            'hello' => [
              'severity' => 10,
            ],
            'world' => [
              'severity' => 11,
            ],
          ],
        ],
        'response' => [
          'status' => 'anything',
        ],
        'get' => 'hello world bla bla bla <&hello>',
        'expected' => [
          'status' => 'anything',
        ],
      ],
    ];
  }

}
