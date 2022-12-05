<?php

namespace Drupal\mymodule;

use Drupal\Core\Entity\EntityTypeManager;
use Drupal\node\Entity\Node;

/**
 * Service to extract the list the latest nodes with field_image
 */
class LatestNodes {

  /**
   * Entity Type Manager
   *
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
   *
   * @return \Drupal\Core\Entity\EntityBase[]|\Drupal\Core\Entity\EntityInterface[]|\Drupal\node\Entity\Node[]
   */
  public function nodeList() {
    $storage = $this->entityTypeManager->getStorage('node');
    $query = $storage->getQuery();
    $node_ids = $query->sort('created', 'DESC')
      ->exists('field_image')
      ->execute();

    return $storage->loadMultiple($node_ids);
  }

}
