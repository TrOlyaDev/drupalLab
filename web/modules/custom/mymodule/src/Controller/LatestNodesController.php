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

class LatestNodesController extends ControllerBase {

  /**
   * Service for retrieving the latest nodes
   * @var \Drupal\mymodule\LatestNodes
   */
  protected $latestNodesService;

  public function __construct(LatestNodes $latestNodes) {
    $this->latestNodesService = $latestNodes;
  }

  public static function create(ContainerInterface $container) {
    return
      new static(
        $container->get('mymodule.latestnodes')
      );
  }

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
        'field_image' => File::load($node->field_image->target_id)->getFileUri(),
        'type' => $node->getType(),
        'created' => date('Y-m-d H:i:s',$node->getCreatedTime()),
      ];
    }

    return [
      '#theme' => 'mymodule.latestnodes',
      '#latestnodes' => $nodesForPrint,
    ];
  }

  public function latestNodesListWithService() {
    $nodes = $this->latestNodesService->nodeList();
    $nodesForPrint = [];
    foreach ($nodes as $node) {
      $nodesForPrint[] = [
        'title' => $node->getTitle(),
        'field_image' => File::load($node->field_image->target_id)->getFileUri(),
        'type' => $node->getType(),
        'created' => date('Y-m-d H:i:s',$node->getCreatedTime()),
      ];
    }

    return [
      '#theme' => 'mymodule.latestnodes',
      '#latestnodes' => $nodesForPrint,
    ];
  }

}
