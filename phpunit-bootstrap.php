<?php

// @codingStandardsIgnoreStart
namespace Drupal\Component\Annotation {
  class Plugin {}
}

namespace Drupal\Component\Plugin {
  class PluginBase {}
}

namespace Drupal\Core\Cache {
  class CacheableJsonResponse {
    public function __construct($response) {
      $this->response = $response;
    }
    public function addCacheableDependency($cache) {
      $this->cache = $cache;
    }
  }
  class CacheableMetadata {
    public static function createFromRenderArray($x) {
      return $x;
    }
  }
}

namespace Drupal\Core\Messenger {
  trait MessengerTrait {}
}

namespace Drupal\Core\Plugin {
  class DefaultPluginManager {}
}

namespace Drupal\Core\StringTranslation {
  trait StringTranslationTrait {}
}

namespace Symfony\Component\HttpFoundation {
  class Request {}
}
// @codingStandardsIgnoreEnd
