<?php

/**
 * @file
 * Generates markup to be displayed.
 * Functionality of this Controller is wired to Drupal in mymodule.routing.yml
 */

namespace Drupal\mymodule\Controller;

use Drupal\Core\Controller\ControllerBase;

class UserInfoController extends ControllerBase {

  public function userSuccessAddedMessage($name, $email, $age) {
    return [
      '#type' => 'markup',
      '#markup' => t('User @name with email @email and age @age added.',
        ['@name' => $name, '@email' => $email, '@age' => $age]),
    ];
  }

}
