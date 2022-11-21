<?php

namespace Drupal\mymodule;

use Drupal\node\Entity\Node;

class LatestNodes {

  function __construct() {

  }

  public function nodeList() {
    $entityQuery = \Drupal::entityQuery('node');
    $node_ids = $entityQuery->sort('created', 'DESC')
      ->exists('field_image')
      ->execute();

    return Node::loadMultiple($node_ids);
  }

}
