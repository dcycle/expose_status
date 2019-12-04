<?php

namespace Drupal\expose_status\Controller;

use Drupal\Core\Cache\CacheableJsonResponse;
use Drupal\Core\Cache\CacheableMetadata;
use Drupal\expose_status\Utilities\Mockables;
use Symfony\Component\HttpFoundation\Request;

/**
 * Controller for the /admin/reports/status/[token] request.
 */
class ExposeStatusController {

  use Mockables;

  /**
   * Generate a response based on a /admin/reports/status/[token] request.
   *
   * @param Request $request
   *   The http request object.
   * @param string $token
   *   The security token.
   *
   * @return CacheableJsonResponse
   *   A Json response.
   *
   * @throws \Exception
   */
  public function get(Request $request, string $token) : CacheableJsonResponse {
    $service = $this->exposeStatusService();

    if ($token && $service->token() == $token) {
      $result = $service->result($request);
    }
    else {
      $result = [
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
      ];
    }
    $response = new CacheableJsonResponse($result['response']);
    $response->addCacheableDependency(CacheableMetadata::createFromRenderArray($result['cache']));
    if (array_key_exists('raw', $result)) {
      $response->addCacheableDependency($result['raw']);
    }
    return $response;
  }

}
