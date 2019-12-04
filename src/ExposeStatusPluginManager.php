<?php

namespace Drupal\expose_status;

use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Plugin\DefaultPluginManager;
use Drupal\expose_status\Annotation\ExposeStatusPluginAnnotation;

/**
 * A plugin manager for sandwich plugins.
 *
 * The SandwichPluginManager class extends the DefaultPluginManager to provide
 * a way to manage sandwich plugins. A plugin manager defines a new plugin type
 * and how instances of any plugin of that type will be discovered, instantiated
 * and more.
 *
 * Using the DefaultPluginManager as a starting point sets up our sandwich
 * plugin type to use annotated discovery.
 *
 * The plugin manager is also declared as a service in
 * expose_status.services.yml so that it can be easily accessed and used
 * anytime we need to work with sandwich plugins.
 */
class ExposeStatusPluginManager extends DefaultPluginManager {

  /**
   * Creates the discovery object.
   *
   * @param \Traversable $namespaces
   *   An object that implements \Traversable which contains the root paths
   *   keyed by the corresponding namespace to look for plugin implementations.
   * @param \Drupal\Core\Cache\CacheBackendInterface $cache_backend
   *   Cache backend instance to use.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   The module handler to invoke the alter hook with.
   */
  public function __construct(\Traversable $namespaces, CacheBackendInterface $cache_backend, ModuleHandlerInterface $module_handler) {
    // We replace the $subdir parameter with our own value.
    // This tells the plugin manager to look for plugins in the
    // 'src/Plugin/ExposeStatusPlugin' subdirectory of any enabled modules.
    // This also serves to define the PSR-4 subnamespace in which plugins will
    // live. Modules can put a plugin class in their own namespace such as
    // Drupal\{module_name}\Plugin\ExposeStatusPlugin\MyPlugin.
    $subdir = 'Plugin/ExposeStatusPlugin';

    // The name of the interface that plugins should adhere to. Drupal will
    // enforce this as a requirement. If a plugin does not implement this
    // interface, Drupal will throw an error.
    $plugin_interface = ExposeStatusPluginInterface::class;

    // The name of the annotation class that contains the plugin definition.
    $plugin_definition_annotation_name = ExposeStatusPluginAnnotation::class;

    parent::__construct($subdir, $namespaces, $module_handler, $plugin_interface, $plugin_definition_annotation_name);
  }

}
