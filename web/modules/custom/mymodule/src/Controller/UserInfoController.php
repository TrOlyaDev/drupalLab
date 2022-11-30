<?php

namespace Drupal\mymodule\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Generates markup with parameters to be displayed.
 */
class UserInfoController extends ControllerBase {

  /**
   * Generates markup with data from AddUserForm.
   *
   * @param string $name
   *   User's name.
   * @param string $email
   *   User's email.
   * @param int $age
   *   User's age.
   *
   * @return array
   *   Array with markup
   */
  public function userSuccessAddedMessage(string $name, string $email, int $age): array {
    return [
      '#type' => 'markup',
      '#markup' => t('User @name with email @email and age @age added.',
        ['@name' => $name, '@email' => $email, '@age' => $age]),
    ];
  }

}
