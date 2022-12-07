<?php

namespace Drupal\mymodule\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Generates markup to be displayed.
 */
class FirstController extends ControllerBase {

  /**
   * Generate simple markup.
   *
   * @return array
   *   An array with the markup.
   */
  public function simpleContent(): array {
    return [
      '#type' => 'markup',
      '#markup' => $this->t('Hello Drupal World!'),
    ];
  }

  /**
   * Generate markup with parameters.
   *
   * @param string $name_1
   *   Name of the first person, who says hello.
   * @param string $name_2
   *   Name of the second person, who says hello.
   *
   * @return array
   *   An array with the markup.
   */
  public function variableContent(string $name_1, string $name_2): array {
    return [
      '#type' => 'markup',
      '#markup' => $this->t('@name1 and @name2 say hello to you!',
        ['@name1' => $name_1, '@name2' => $name_2]),
    ];
  }

}
