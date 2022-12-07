<?php

namespace Drupal\mymodule\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\mymodule\LatestNodes;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Display the latest nodes list.
 */
class LatestNodesController extends ControllerBase {

  /**
   * Service for retrieving the latest nodes.
   *
   * @var \Drupal\mymodule\LatestNodes
   */
  protected $latestNodesService;

  /**
   * Create an instance of LatestNodesController.
   *
   * @param \Drupal\mymodule\LatestNodes $latestNodes
   *   The latestnodes service.
   */
  public function __construct(LatestNodes $latestNodes) {
    $this->latestNodesService = $latestNodes;
  }

  /**
   * {@inheritDoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('mymodule.latestnodes')
    );
  }

  /**
   * Create and display the latest nodes list.
   *
   * @return array
   *   The nodes array.
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function latestNodesList() {
    $storage = $this->entityTypeManager()->getStorage('node');
    $query = $storage->getQuery();
    $node_ids = $query->sort('created', 'DESC')
      ->exists('field_image')
      ->execute();
    $nodes = $storage->loadMultiple($node_ids);
    foreach ($nodes as $node) {
      $node->teaser = $this->entityTypeManager()->getViewBuilder('node')->view($node, 'teaser');
    }

    return [
      '#theme' => 'mymodule.latestnodes',
      '#latestnodes' => $nodes,
      '#cache' => [
        'tags' => ['node_list'],
      ],
    ];
  }

  /**
   * Create and display the latest nodes list using custom service.
   *
   * @return array
   *   The nodes array.
   */
  public function latestNodesListWithService(): array {
    $nodes = $this->latestNodesService->nodeList();
    foreach ($nodes as $node) {
      $node->teaser = $this->entityTypeManager()->getViewBuilder('node')->view($node, 'teaser');
    }

    return [
      '#theme' => 'mymodule.latestnodes',
      '#latestnodes' => $nodes,
      '#cache' => [
        'tags' => ['node_list'],
      ],
    ];
  }

}
