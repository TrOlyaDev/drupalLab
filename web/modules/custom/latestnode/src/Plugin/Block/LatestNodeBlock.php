<?php

namespace Drupal\latestnode\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Render\Renderer;
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
   * Service latestnode.latest_node
   *
   * @var \Drupal\latestnode\LatestNode
   */
  protected $latestNodeService;

  /**
   * The renderer
   *
   * @var \Drupal\Core\Render\Renderer
   */
  protected $renderer;

  /**
   * The config factory
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * {@inheritDoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, LatestNode $latestNodeService, Renderer $renderer, ConfigFactoryInterface $configFactory) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->latestNodeService = $latestNodeService;
    $this->renderer = $renderer;
    $this->configFactory = $configFactory;
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
        'created' => date('Y-m-d H:i:s',$node->getCreatedTime()),
        'url' => $node->toLink()->toString(),
      ];
    }
    $build['latestnode_block'] = [
      '#theme' => 'latestnode.block',
      '#latestnodes' => $nodesForPrint,
      '#cache' => [
        'tags' => ['node_list'],
      ],
    ];
    $this->renderer->addCacheableDependency($build, $this->configFactory->get('latestnode.settings'));

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
      $container->get('latestnode.latest_node'),
      $container->get('renderer'),
      $container->get('config.factory')
    );
  }

}
