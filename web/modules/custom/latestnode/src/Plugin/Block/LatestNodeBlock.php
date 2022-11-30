<?php

namespace Drupal\latestnode\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\latestnode\LatestNode;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a 'Latest Node' block.
 *
 * @Block(
 *  id = "latestnode_block",
 *  admin_label = @Translation("Latest Node Block"),
 * )
 */
class LatestNodeBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * Plugin id
   */
  public $id;

  /**
   * Admin label
   */
  public $admin_label;

  /**
   * Service latestnode.latest_node
   *
   * @var \Drupal\latestnode\LatestNode
   */
  protected $latestNodeService;

  /**
   * {@inheritDoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, LatestNode $latestNodeService) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->latestNodeService = $latestNodeService;
  }

  /**
   * {@inheritdoc}
   */
  public function build() {

    $nodes = $this->latestNodeService->nodeList();
    $nodesForPrint = [];
    foreach ($nodes as $node) {
      $nodesForPrint[] = [
        'type' => $node->getType(),
        'created' => date('Y-m-d H:i:s', $node->getCreatedTime()),
        'url' => $node->toLink()->toString(),
      ];
    }
    $build['latestnode_block'] = [
      '#theme' => 'latestnode.block',
      '#latestnodes' => $nodesForPrint,
      '#cache' => [
        'max-age' => 0, //node_list, config
      ],
    ];

    return $build;
  }

  /**
   * {@inheritDoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('latestnode.latest_node')
    );
  }

}
