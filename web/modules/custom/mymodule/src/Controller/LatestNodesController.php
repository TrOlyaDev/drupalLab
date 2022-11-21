<?php

/**
 * @file
 * Output the latest nodes
 * Functionality of this Controller is wired to Drupal in mymodule.routing.yml
 */

namespace Drupal\mymodule\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\file\Entity\File;
use Drupal\node\Entity\Node;

class LatestNodesController extends ControllerBase {

  public function latestNodesList() {
    $entityQuery = \Drupal::entityQuery('node');
    $node_ids = $entityQuery->sort('created', 'DESC')
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
    $nodes = \Drupal::service('mymodule.latestnodes')->nodeList();
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
