<?php

namespace Drupal\mymodule;

use Drupal\Core\Controller\ControllerBase;
use Drupal\node\Entity\Node;

class LatestNodes extends ControllerBase {

  public function nodeList() {
    $query = $this->entityTypeManager()->getStorage('node')->getQuery();
    $node_ids = $query->sort('created', 'DESC')
      ->exists('field_image')
      ->execute();

    return Node::loadMultiple($node_ids);
  }

}
