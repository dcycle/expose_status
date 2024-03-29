<?php

namespace Drupal\expose_status\Controller;

use Drupal\Core\Cache\CacheableJsonResponse;
use Drupal\Core\Cache\CacheableMetadata;
use Drupal\Core\Controller\ControllerBase;
use Drupal\expose_status\ExposeStatusInterface;
use Drupal\expose_status\Utilities\Mockables;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Controller for the /admin/reports/status/[token] request.
 */
class ExposeStatusController extends ControllerBase {

  use Mockables;

  /**
   * The injected expose_status service.
   *
   * @var \Drupal\expose_status\ExposeStatusInterface
   */
  protected $exposeStatus;

  /**
   * Constructs a new ExposeStatusController object.
   *
   * @param \Drupal\expose_status\ExposeStatusInterface $expose_status
   *   An injected expose_status service.
   */
  public function __construct(ExposeStatusInterface $expose_status) {
    $this->exposeStatus = $expose_status;
  }

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
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $expose_status = $container->get('expose_status');
    // PHPStan complains that using this should make the class final, but
    // this is widely used in Drupal, for example in
    // ./core/lib/Drupal/Core/Entity/Controller/EntityController.php.
    // Declaring the class final would make it unmockable.
    // @phpstan-ignore-next-line
    return new static($expose_status);
  }

  /**
   * Getter for the injected expose_status service.
   *
   * @return \Drupal\expose_status\ExposeStatusInterface
   *   The injected expose_status service.
   */
  public function exposeStatusService() : ExposeStatusInterface {
    return $this->exposeStatus;
  }

  /**
   * Generate a response based on a /admin/reports/status/[token] request.
   *
   * This assumes that access has already been determined using the ::access()
   * method.
   *
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   The http request object.
   *
   * @return \Drupal\Core\Cache\CacheableJsonResponse
   *   A Json response.
   *
   * @throws \Exception
   */
  public function get(Request $request) : CacheableJsonResponse {
    $result = $this->exposeStatusService()->result($request);
    return $this->cacheableResponse($result);
  }

}
