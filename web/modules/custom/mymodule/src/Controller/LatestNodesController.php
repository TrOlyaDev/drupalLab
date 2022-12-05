<?php

/**
 * @file
 * Output the latest nodes
 * Functionality of this Controller is wired to Drupal in mymodule.routing.yml
 */

namespace Drupal\mymodule\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\EntityTypeRepository;
use Drupal\file\Entity\File;
use Drupal\mymodule\LatestNodes;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Controller to output the list of the latest nodes with field_image
 */
class LatestNodesController extends ControllerBase {

  /**
   * Service for retrieving the latest nodes
   *
   * @var \Drupal\mymodule\LatestNodes
   */
  protected $latestNodesService;

  /**
   * {@inheritdoc}
   */
  public function __construct(LatestNodes $latestNodes) {
    $this->latestNodesService = $latestNodes;
  }

  /**
   * {@inheritDoc}
   */
  public static function create(ContainerInterface $container) {
    return
      new static(
        $container->get('mymodule.latestnodes')
      );
  }

  /**
   * Display the latest nodes list
   *
   * @return array
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
      '#cache' =>[
        'tags' => ['node_list'],
      ]
    ];
  }

  /**
   * Display the latest nodes list using custom service
   *
   * @return array
   */
  public function latestNodesListWithService() {
    $nodes = $this->latestNodesService->nodeList();
    foreach ($nodes as $node) {
      $node->teaser = $this->entityTypeManager()->getViewBuilder('node')->view($node, 'teaser');
    }

    return [
      '#theme' => 'mymodule.latestnodes',
      '#latestnodes' => $nodes,
      '#cache' =>[
        'tags' => ['node_list'],
      ]
    ];
  }

}
