<?php

/**
 * @file
 * Generates markup to be displayed.
 * Functionality of this Controller is wired to Drupal in mymodule.routing.yml
 */

namespace Drupal\mymodule\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Generates markup with parameters to be displayed
 */
class UserInfoController extends ControllerBase {

  /**
   * Generates markup with data from AddUserForm
   *
   * @param $name
   * @param $email
   * @param $age
   *
   * @return array
   */
  public function userSuccessAddedMessage($name, $email, $age) {
    return [
      '#type' => 'markup',
      '#markup' => t('User @name with email @email and age @age added.',
        ['@name' => $name, '@email' => $email, '@age' => $age]),
    ];
  }

}
