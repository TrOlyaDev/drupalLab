<?php

namespace Drupal\latestnode;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\EntityTypeManager;

/**
 * Service to extract the list of the latest nodes
 */
class LatestNode {

  /**
   * Config id
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
   * Create the latest nodes list
   *
   * @return \Drupal\Core\Entity\EntityBase[]|\Drupal\Core\Entity\EntityInterface[]|\Drupal\node\Entity\Node[]
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function nodeList() {
    $storage = $this->entityTypeManager->getStorage('node');
    $config = $this->configFactory->getEditable(static::SETTINGS);
    $node_type = $config->get('node_type');
    $node_count = $config->get('node_count');
    $nodeQuery = $storage->getQuery();
    $node_id = $nodeQuery->condition('type', $node_type)
      ->sort('created', 'DESC')
      ->range(0,$node_count)
      ->execute();

    return $storage->loadMultiple($node_id);
  }

}
