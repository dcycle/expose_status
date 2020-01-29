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
   * Access callback for the expose_status.status route.
   *
   * @param string $token
   *   The security token.
   *
   * @return \Drupal\Core\Access\AccessResultInterface
   *   Allowed or denied.
   */
  public function access(string $token) {
    if ($token && $this->exposeStatusService()->token() == $token) {
      return $this->accessAllowed();
    }
    return $this->accessForbidden();
  }

  /**
   * Mockable wrapper around a cacheable JSON response with metadata.
   */
  public function cacheableResponse(array $result) : CacheableJsonResponse {
    $response = new CacheableJsonResponse($result['response']);
    $response->addCacheableDependency(CacheableMetadata::createFromRenderArray($result['cache']));
    return $response;
  }

  /**
   * Generate a response based on a /admin/reports/status/[token] request.
   *
   * This assumes that access has already been determined using the ::access()
   * method.
   *
   * @param Request $request
   *   The http request object.
   *
   * @return CacheableJsonResponse
   *   A Json response.
   *
   * @throws \Exception
   */
  public function get(Request $request) : CacheableJsonResponse {
    $result = $this->exposeStatusService()->result($request);
    return $this->cacheableResponse($result);
  }

}
