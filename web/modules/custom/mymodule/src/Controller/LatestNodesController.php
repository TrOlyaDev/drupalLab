<?php

/**
 * @file
 * Output the latest nodes
 * Functionality of this Controller is wired to Drupal in mymodule.routing.yml
 */

namespace Drupal\mymodule\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\file\Entity\File;
use Drupal\mymodule\LatestNodes;
use Drupal\node\Entity\Node;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Display the latest nodes list
 */
class LatestNodesController extends ControllerBase {

  /**
   * Service for retrieving the latest nodes
   *
   * @var \Drupal\mymodule\LatestNodes
   */
  protected $latestNodesService;

  /**
   * Create an instance of LatestNodesController
   *
   * @param \Drupal\mymodule\LatestNodes $latestNodes
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
   * Create and display the latest nodes list
   *
   * @return array
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function latestNodesList() {
    $query = $this->entityTypeManager()->getStorage('node')->getQuery();
    $node_ids = $query->sort('created', 'DESC')
      ->exists('field_image')
      ->execute();
    $nodes = Node::loadMultiple($node_ids);
    $nodesForPrint = [];
    foreach ($nodes as $node) {
      $nodesForPrint[] = [
        'title' => $node->getTitle(),
        'field_image' => File::load($node->field_image->target_id)
          ->getFileUri(),
        'type' => $node->getType(),
        'created' => date('Y-m-d H:i:s', $node->getCreatedTime()),
      ];
    }

    return [
      '#theme' => 'mymodule.latestnodes',
      '#latestnodes' => $nodesForPrint,
    ];
  }

  /**
   * Create and display the latest nodes list using custom service
   *
   * @return array
   */
  public function latestNodesListWithService() {
    $nodes = $this->latestNodesService->nodeList();
    $nodesForPrint = [];
    foreach ($nodes as $node) {
      $nodesForPrint[] = [
        'title' => $node->getTitle(),
        'field_image' => File::load($node->field_image->target_id)
          ->getFileUri(),
        'type' => $node->getType(),
        'created' => date('Y-m-d H:i:s', $node->getCreatedTime()),
      ];
    }

    return [
      '#theme' => 'mymodule.latestnodes',
      '#latestnodes' => $nodesForPrint,
    ];
  }

}
