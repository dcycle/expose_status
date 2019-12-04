<?php

namespace Drupal\Tests\expose_status\Unit\Controller;

use Drupal\expose_status\Controller\ExposeStatusController;
use Drupal\expose_status\ExposeStatus;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

/**
 * Test ExposeStatusController.
 *
 * @group expose_status_phpunit_only
 */
class ExposeStatusControllerTest extends TestCase {

  /**
   * Test for get().
   *
   * @param string $message
   *   The test message.
   * @param string $user_token
   *   The mock user token.
   * @param string $system_token
   *   The mock system token.
   * @param array $expected
   *   The expected result; ignored if an exception is expected.
   *
   * @cover ::get
   * @dataProvider providerGet
   */
  public function testGet(string $message, string $user_token, string $system_token, array $expected) {
    $object = $this->getMockBuilder(ExposeStatusController::class)
      // NULL = no methods are mocked; otherwise list the methods here.
      ->setMethods([
        'exposeStatusService',
      ])
      ->disableOriginalConstructor()
      ->getMock();

    // @codingStandardsIgnoreStart
    $object->method('exposeStatusService')
      ->willReturn(new class($system_token) extends ExposeStatus {
        public function __construct($token) {
          $this->token = $token;
        }
        public function result(Request $request) : array {
          return [
            'cache' => ['some-cache-data'],
            'response' => ['some-valid-response'],
          ];
        }
        public function token(bool $obfuscate = FALSE, bool $reset = FALSE) : string {
          return $this->token;
        }
      });

    $output_object = $object->get(new Request, $user_token);
    $output = [
      'response' => $output_object->response,
      'cache' => $output_object->cache,
    ];

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
   * Provider for testGet().
   */
  public function providerGet() {
    return [
      [
        'message' => 'Invalid token',
        'user_token' => 'hello',
        'system_token' => 'world',
        'expected' => [
          'response' => [
            'error' => 'Token is not valid',
          ],
          'cache' => [
            '#cache' => [
              'max-age' => 0,
              'contexts' => [
                'url',
              ],
              'tags' => [
                'expose-status-security-token-has-changed',
              ],
            ],
          ],
        ],
      ],
      [
        'message' => 'No token',
        'user_token' => '',
        'system_token' => '',
        'expected' => [
          'response' => [
            'error' => 'Token is not valid',
          ],
          'cache' => [
            '#cache' => [
              'max-age' => 0,
              'contexts' => [
                'url',
              ],
              'tags' => [
                'expose-status-security-token-has-changed',
              ],
            ],
          ],
        ],
      ],
      [
        'message' => 'No token, but system token exists',
        'user_token' => '',
        'system_token' => 'hello',
        'expected' => [
          'response' => [
            'error' => 'Token is not valid',
          ],
          'cache' => [
            '#cache' => [
              'max-age' => 0,
              'contexts' => [
                'url',
              ],
              'tags' => [
                'expose-status-security-token-has-changed',
              ],
            ],
          ],
        ],
      ],
      [
        'message' => 'Valid token',
        'user_token' => 'hello',
        'system_token' => 'hello',
        'expected' => [
          'response' => [
            'some-valid-response',
          ],
          'cache' => [
            'some-cache-data',
          ],
        ],
      ],
    ];
  }

}
