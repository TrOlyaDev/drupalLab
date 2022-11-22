<?php

namespace Drupal\latestnode;

use Drupal\Core\Entity\EntityTypeManager;
use Drupal\node\Entity\Node;

/**
 * Service latestnode.latest_node
 */
class LatestNode {

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
   * Create the latest node list
   * @return \Drupal\Core\Entity\EntityBase[]|\Drupal\Core\Entity\EntityInterface[]|\Drupal\node\Entity\Node[]
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function nodeList() {
    $nodeQuery = $this->entityTypeManager->getStorage('node')->getQuery();
    $node_id = $nodeQuery->condition('type', 'blog')
      ->sort('created', 'DESC')
      ->range(0,1)
      ->execute();

    return Node::loadMultiple($node_id);
  }

}
