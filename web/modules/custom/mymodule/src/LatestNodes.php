<?php

namespace Drupal\mymodule;

use Drupal\Core\Entity\EntityTypeManager;

/**
 * Service to extract the list the latest nodes with field_image.
 */
class LatestNodes {

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManager
   */
  protected $entityTypeManager;

  /**
   * Create an instance of LatestNodes.
   *
   * @param \Drupal\Core\Entity\EntityTypeManager $entityTypeManager
   *   The entity type manager.
   */
  public function __construct(EntityTypeManager $entityTypeManager) {
    $this->entityTypeManager = $entityTypeManager;
  }

  /**
   * Create the latest nodes list.
   *
   * @return \Drupal\Core\Entity\EntityBase[]|\Drupal\Core\Entity\EntityInterface[]|\Drupal\node\Entity\Node[]
   *   The nodes list
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
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
