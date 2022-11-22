<?php

namespace Drupal\mymodule;

use Drupal\node\Entity\Node;

class LatestNodes {

  /**
   * Entity Type Manager
   * @var \Drupal\Core\Entity\EntityTypeManager
   */
  protected $entityTypeManager;

  /**
   * {@inheritDoc}
   */
  function __construct(EntityTypeManager $entityTypeManager) {
    $this->entityTypeManager = $entityTypeManager;
  }

  /**
   * Create the latest nodes list
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
