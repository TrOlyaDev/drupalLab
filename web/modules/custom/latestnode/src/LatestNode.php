<?php

namespace Drupal\latestnode;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\EntityTypeManager;

/**
 * Service to extract the list of the latest nodes.
 */
class LatestNode {

  /**
   * Config settings.
   *
   * @var string
   */
  const SETTINGS = 'latestnode.settings';

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManager
   */
  protected $entityTypeManager;

  /**
   * The config factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * Create an entity of LatestNode.
   *
   * @param \Drupal\Core\Entity\EntityTypeManager $entityTypeManager
   *   The entity type manager.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $configFactory
   *   The config factory.
   */
  public function __construct(EntityTypeManager $entityTypeManager, ConfigFactoryInterface $configFactory) {
    $this->entityTypeManager = $entityTypeManager;
    $this->configFactory = $configFactory;
  }

  /**
   * Create the latest nodes list.
   *
   * @return \Drupal\Core\Entity\EntityBase[]|\Drupal\Core\Entity\EntityInterface[]|\Drupal\node\Entity\Node[]
   *   The nodes array.
   *
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
      ->range(0, $node_count)
      ->execute();

    return $storage->loadMultiple($node_id);
  }

}
