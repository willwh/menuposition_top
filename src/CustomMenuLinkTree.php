<?php
/**
* @file
* Contains Drupal\menuposition_top\CustomMenuLinkTree.
*/

namespace Drupal\menuposition_top;

use Drupal\Core\Controller\ControllerResolverInterface;
use Drupal\Core\Cache\CacheableMetadata;
use Drupal\Core\Menu\MenuLinkTree;
use Drupal\Core\Menu\MenuActiveTrailInterface;
use Drupal\Core\Menu\MenuLinkManagerInterface;
use Drupal\Core\Menu\MenuTreeStorageInterface;
use Drupal\Core\Routing\CurrentRouteMatch;
use Drupal\Core\Routing\RouteProviderInterface;
use Drupal\node\NodeInterface;

/**
* Extend the MenuLinkTree class.
*/
class CustomMenuLinkTree extends MenuLinkTree {
/**
* The current route match service.
*
* @var \Drupal\Core\Routing\CurrentRouteMatch
*/
  protected $routeMatch;

/**
* {@inheritdoc}
*
* @param \Drupal\Core\Routing\CurrentRouteMatch $route_match
*   The current route match service.
*/
  public function __construct(MenuTreeStorageInterface $tree_storage, MenuLinkManagerInterface $menu_link_manager, RouteProviderInterface $route_provider, MenuActiveTrailInterface $menu_active_trail, ControllerResolverInterface $controller_resolver, CurrentRouteMatch $route_match) {
    parent::__construct($tree_storage, $menu_link_manager, $route_provider, $menu_active_trail, $controller_resolver);
    $this->routeMatch = $route_match;
  }

/**
* {@inheritdoc}
*/
  protected function buildItems(array $tree, CacheableMetadata &$tree_access_cacheability, CacheableMetadata &$tree_link_cacheability) {
    $items = parent::buildItems($tree, $tree_access_cacheability, $tree_link_cacheability);

    foreach ($items as $key => $item) {
      // Only operate if there is a menu item that points
      // to the route that should get the active-trail class.
      if ($item['url']->getRouteName() == 'view.blog.page_1') {
        // Add cacheability metadata.
        $tree_link_cacheability->addCacheContexts(['url']);

        // Set the in_active_trail key to TRUE if a blog node is displayed.
        $node = $this->routeMatch->getParameter('node');

        if ($node instanceof NodeInterface && $node->bundle() == 'blog') {
          $items[$key]['in_active_trail'] = TRUE;
        }
      }
    }
    return $items;
  }
}