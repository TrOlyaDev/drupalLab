<?php

namespace Drupal\mymodule;

use Drupal\Core\Entity\EntityTypeManager;
use Drupal\node\Entity\Node;

/**
 * Creates the latest nodes with field_image list
 */
class LatestNodes {

  /**
   * Entity Type Manager
   *
   * @var \Drupal\Core\Entity\EntityTypeManager
   */
  protected $entityTypeManager;

  /**
   * Create an instance of LatestNodes
   *
   * @param \Drupal\Core\Entity\EntityTypeManager $entityTypeManager
   */
  function __construct(EntityTypeManager $entityTypeManager) {
    $this->entityTypeManager = $entityTypeManager;
  }

  /**
   * Create the latest nodes list
   *
   * @return \Drupal\Core\Entity\EntityBase[]|\Drupal\Core\Entity\EntityInterface[]|\Drupal\node\Entity\Node[]
   */
  public function nodeList() {
    $query = $this->entityTypeManager()->getStorage('node')->getQuery();
    $node_ids = $query->sort('created', 'DESC')
      ->exists('field_image')
      ->execute();

    return Node::loadMultiple($node_ids);
  }

}
