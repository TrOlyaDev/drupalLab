<?php

/**
 * @file
 * Generates markup to be displayed.
 * Functionality of this Controller is wired to Drupal in mymodule.routing.yml
 */

namespace Drupal\mymodule\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Generates markup to be displayed
 */
class FirstController extends ControllerBase {

  /**
   * Generate simple markup
   *
   * @return array
   */
  public function simpleContent() {
    return [
      '#type' => 'markup',
      '#markup' => t('Hello Drupal World!'),
    ];
  }

  /**
   * Generate markup with parameters
   *
   * @param $name_1
   * @param $name_2
   *
   * @return array
   */
  public function variableContent($name_1, $name_2) {
    return [
      '#type' => 'markup',
      '#markup' => t('@name1 and @name2 say hello to you!',
        ['@name1' => $name_1, '@name2' => $name_2]),
    ];
  }

}
