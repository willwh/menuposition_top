<?php
/**
* @file
* Contains Drupal\custom\CustomServiceProvider.
*/

namespace Drupal\menuposition_top;

use Drupal\Core\DependencyInjection\ContainerBuilder;
use Drupal\Core\DependencyInjection\ServiceProviderBase;
use Symfony\Component\DependencyInjection\Reference;

/**
* Alter the service container to use a custom
* class and to add an additional argument to the
* menu.link_tree service.
*/
class CustomServiceProvider extends ServiceProviderBase {

/**
* {@inheritdoc}
*/
  public function alter(ContainerBuilder $container) {
  $definition = $container->getDefinition('menu.link_tree');

  // Use custom CustomMenuLinkTree class instead of the
  // default MenuLinkTree class.
  $definition->setClass('Drupal\custom\CustomMenuLinkTree');

  // Provide an additional argument to the service to add
  // the current_route_match service.
  $definition->addArgument(new Reference('current_route_match'));
  }
}