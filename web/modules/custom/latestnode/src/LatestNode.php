<?php

namespace Drupal\latestnode;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\EntityTypeManager;
use Drupal\node\Entity\Node;

/**
 * Service latestnode.latest_node
 */
class LatestNode {

  /**
   * Config settings.
   *
   * @var string
   */
  const SETTINGS = 'latestnode.settings';

  /**
   * Entity Type Manager
   *
   * @var \Drupal\Core\Entity\EntityTypeManager
   */
  protected $entityTypeManager;

  /**
   * Config Factory
   *
   * @var \Drupal\latestnode\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * {@inheritDoc}
   */
  function __construct(EntityTypeManager $entityTypeManager, ConfigFactoryInterface $configFactory) {
    $this->entityTypeManager = $entityTypeManager;
    $this->configFactory = $configFactory;
  }

  /**
   * Create the latest node list
   *
   * @return \Drupal\Core\Entity\EntityBase[]|\Drupal\Core\Entity\EntityInterface[]|\Drupal\node\Entity\Node[]
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function nodeList() {
    $config = $this->configFactory->getEditable(static::SETTINGS);
    $node_type = $config->get('node_type');
    $node_count = $config->get('node_count');
    $nodeQuery = $this->entityTypeManager->getStorage('node')->getQuery();
    $node_id = $nodeQuery->condition('type', $node_type)
      ->sort('created', 'DESC')
      ->range(0, $node_count)
      ->execute();

    return Node::loadMultiple($node_id);
  }

}
