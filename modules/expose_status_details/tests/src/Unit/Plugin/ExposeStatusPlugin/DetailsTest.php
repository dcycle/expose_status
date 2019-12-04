<?php

namespace Drupal\Tests\expose_status_details\Unit\Plugin\ExposeStatusPlugin;

use Drupal\expose_status_details\Plugin\ExposeStatusPlugin\Details;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

/**
 * Test Details.
 *
 * @group expose_status_phpunit_only
 */
class DetailsTest extends TestCase {

  /**
   * Test for alterResponse().
   *
   * @cover ::alterResponse
   * @dataProvider providerAlterResponse
   */
  public function testAlterResponse(string $message, array $result, array $response, string $get, array $expected) {
    $object = $this->getMockBuilder(Details::class)
      // NULL = no methods are mocked; otherwise list the methods here.
      ->setMethods(NULL)
      ->disableOriginalConstructor()
      ->getMock();

    $output = [];

    $object->alterResponse(new Request(), [
      'raw' => [
        'a' => [
          'severity' => 1,
        ],
        'b' => [
          'severity' => 2,
        ],
      ],
    ], $output);

    $message = 'Details should be added to output';
    $expected = [
      'details' => [
        'a' => [
          'severity' => 1,
        ],
        'b' => [
          'severity' => 2,
        ],
      ],
    ];

    if ($output != $expected) {
      print_r([
        'message' => $message,
        'output' => $output,
        'expected' => $expected,
      ]);
    }

    $this->assertTrue($output == $expected, $message);
  }

}
