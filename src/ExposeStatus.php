<?php

namespace Drupal\expose_status;

use Drupal\Component\Utility\Crypt;
use Drupal\Core\Cache\CacheTagsInvalidatorInterface;
use Drupal\Core\Messenger\MessengerTrait;
use Drupal\Core\Render\RenderContext;
use Drupal\Core\Render\RendererInterface;
use Drupal\Core\State\State;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\system\SystemManager;
use Symfony\Component\HttpFoundation\Request;

/**
 * ExposeStatus singleton. Use \Drupal::service('expose_status').
 */
class ExposeStatus implements ExposeStatusInterface {

  use MessengerTrait;
  use StringTranslationTrait;

  /**
   * The injected renderer.
   *
   * @var \Drupal\Core\Render\RendererInterface
   */
  protected $renderer;

  /**
   * The injected cache_tags.invalidator.
   *
   * @var \Drupal\Core\Cache\CacheTagsInvalidatorInterface
   */
  protected $cacheTagsInvalidator;

  /**
   * The injected system.manager.
   *
   * @var \Drupal\system\SystemManager
   */
  protected $systemManager;

  /**
   * The state store.
   *
   * @var \Drupal\Core\State\State
   */
  protected $state;

  /**
   * Constructs a new ExposeStatus object.
   *
   * @param \Drupal\Core\Render\RendererInterface $renderer
   *   An injected renderer service.
   * @param \Drupal\Core\Cache\CacheTagsInvalidatorInterface $cache_tags_invalidator
   *   An injected cache_tags.invalidator service.
   * @param \Drupal\system\SystemManager $system_manager
   *   An injected system.manager service.
   * @param \Drupal\Core\State\State $state
   *   An injected state service.
   */
  public function __construct(RendererInterface $renderer, CacheTagsInvalidatorInterface $cache_tags_invalidator, SystemManager $system_manager, State $state) {
    $this->renderer = $renderer;
    $this->cacheTagsInvalidator = $cache_tags_invalidator;
    $this->systemManager = $system_manager;
    $this->state = $state;
  }

  /**
   * {@inheritdoc}
   */
  public function baseUrl() : string {
    global $base_url;

    return $base_url;
  }

  /**
   * {@inheritdoc}
   */
  public function exampleUrls(bool $obfuscate) : array {
    $base_url = $this->baseUrl();
    $token = $this->token($obfuscate);

    return array_merge([
      $base_url . '/admin/reports/status/expose/' . $token,
    ], $this->plugins()->exampleUrls($base_url, $token));
  }

  /**
   * {@inheritdoc}
   */
  public function generateToken() : string {
    return Crypt::hashBase64(random_bytes(128));
  }

  /**
   * {@inheritdoc}
   */
  public function hookRequirements(string $phase) : array {
    if ($phase == 'runtime') {
      $this->messenger()->addMessage($this->instructions(TRUE) . ' ' . $this->t('To see the actual token value (instaed of ******), which we are not showing here due to security considerations, use drush ev "expose_status_instructions()"'));
    }
    return [];
  }

  /**
   * {@inheritdoc}
   */
  public function instructions(bool $obfuscate = FALSE) : string {
    return $this->t('You can get the overall status in JSON format using, for example, @urls.', [
      '@urls' => implode(', ', $this->exampleUrls($obfuscate)),
    ]);
  }

  /**
   * {@inheritdoc}
   */
  public function plugins() : ExposeStatusPluginCollection {
    return ExposeStatusPluginCollection::instance();
  }

  /**
   * {@inheritdoc}
   */
  public function rawData() : array {
    $this->plugins()->prepare();

    $return = [];

    // Certain items in the requirements list might contain leaked caching
    // information. For example, some modules may put a node URL within
    // the description of one of the requirements defined with
    // hook_requirements. If we do not use our own render context, and then
    // try to cache our response, Drupal will complain that
    // "leaked metadata was detected" and refuse to render the resulting
    // JSON.
    // See https://blog.dcycle.com/blog/2018-01-24.
    $system_manager = $this->systemManager;
    // @codingStandardsIgnoreStart
    $this->renderer->executeInRenderContext(new RenderContext(), function () use (&$return, $system_manager) {
      $return = $system_manager->listRequirements();
    });
    // @codingStandardsIgnoreEnd

    return $return;
  }

  /**
   * {@inheritdoc}
   */
  public function rawDataToStatus(array $raw) : string {
    $return = 'ok';

    foreach ($raw as $line) {
      if (isset($line['severity']) && $line['severity'] > 0) {
        $return = 'issues found; please check';
        break;
      }
    }

    return $return;
  }

  /**
   * {@inheritdoc}
   */
  public function result(Request $request) : array {
    $raw = $this->rawData();

    $result = [
      'cache' => [
        '#cache' => [
          'max-age' => 0,
          // If plugins remove the 'url' context, it will be added back
          // because we don't want a call with the wrong token to return
          // sensitive cached data.
          'contexts' => [
            'url',
          ],
          'tags' => [
            'expose-status-security-token-has-changed',
          ],
        ],
      ],
      'unaltered_raw' => $raw,
      'raw' => $raw,
    ];

    $this->plugins()->alterResult($request, $result);

    $response = [
      'status' => $this->rawDataToStatus($result['raw']),
      'generated' => date('Y-m-d H:i:s'),
    ];

    $this->plugins()->alterResponse($request, $result, $response);
    $result['response'] = $response;

    // Reinstate 'url' in case it has been removed, for reasons stated in the
    // comment, above.
    $result['cache']['#cache']['contexts'] = ['url'];

    return $result;
  }

  /**
   * {@inheritdoc}
   */
  public function token(bool $obfuscate = FALSE, bool $reset = FALSE) : string {
    $candidate = $this->state->get('expose_status_token', '');
    if (!$candidate || $reset) {
      $candidate = $this->generateToken();
      $this->cacheTagsInvalidator
        ->invalidateTags([
          'expose-status-security-token-has-changed',
        ]);
      $this->state->set('expose_status_token', $candidate);
    }
    return $obfuscate ? '*****' : $candidate;
  }

}
