<?php

namespace Drupal\Tests\expose_status\Unit\Controller;

use Drupal\Core\Cache\CacheableJsonResponse;
use Drupal\expose_status\Controller\ExposeStatusController;
use Drupal\expose_status\ExposeStatus;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

/**
 * Test ExposeStatusController.
 *
 * @group expose_status
 */
class ExposeStatusControllerTest extends TestCase {

  /**
   * Test for access().
   *
   * @param string $message
   *   The test message.
   * @param string $user_token
   *   The mock user token.
   * @param string $system_token
   *   The mock system token.
   * @param string $expected
   *   The expected result; ignored if an exception is expected.
   *
   * @cover ::access
   * @dataProvider providerAccess
   */
  public function testAccess(string $message, string $user_token, string $system_token, $expected) {
    $object = $this->getMockBuilder(ExposeStatusController::class)
      // NULL = no methods are mocked; otherwise list the methods here.
      ->setMethods([
        'accessAllowed',
        'accessForbidden',
        'exposeStatusService',
      ])
      ->disableOriginalConstructor()
      ->getMock();

    $object->method('accessAllowed')
      ->willReturn('allowed');
    $object->method('accessForbidden')
      ->willReturn('forbidden');
    // @codingStandardsIgnoreStart
    $object->method('exposeStatusService')
      ->willReturn(new class($system_token) extends ExposeStatus {
        public function __construct($token) {
          $this->token = $token;
        }
        public function token(bool $obfuscate = FALSE, bool $reset = FALSE) : string {
          return $this->token;
        }
      });

    $output = $object->access($user_token);

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
   * Provider for testAccess().
   */
  public function providerAccess() {
    return [
      [
        'message' => 'Wrong token',
        'user_token' => 'hello',
        'system_token' => 'world',
        'expected' => 'forbidden',
      ],
      [
        'message' => 'No token',
        'user_token' => '',
        'system_token' => '',
        'expected' => 'forbidden',
      ],
      [
        'message' => 'Right token',
        'user_token' => 'hello world',
        'system_token' => 'hello world',
        'expected' => 'allowed',
      ],
    ];
  }

  /**
   * Test for get().
   *
   * @cover ::get
   */
  public function testGet() {
    $object = $this->getMockBuilder(ExposeStatusController::class)
      // NULL = no methods are mocked; otherwise list the methods here.
      ->setMethods([
        'cacheableResponse',
        'exposeStatusService',
      ])
      ->disableOriginalConstructor()
      ->getMock();

    // @codingStandardsIgnoreStart
    $object->method('exposeStatusService')
      ->willReturn(new class() extends ExposeStatus {
        public function result(Request $request) : array {
          return [
            'cache' => ['some-cache-data'],
            'response' => ['some-valid-response'],
          ];
        }
      });

    $object->method('cacheableResponse')
      ->will($this->returnCallback(function ($result) {
        return new class($result) extends CacheableJsonResponse {
          public function __construct($result) {
            $this->result = $result;
          }
          public function result() {
            return $this->result;
          }
        };
      }));

    $message = 'Valid token';
    $expected = [
      'response' => [
        'some-valid-response',
      ],
      'cache' => [
        'some-cache-data',
      ],
    ];

    $output = $object->get(new Request())->result();

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

}
