<?php

/**
 * @file
 * Display student info
 * Functionality of this Controller is wired to Drupal in student_registration.routing.yml
 */

namespace Drupal\student_registration\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class StudentViewController extends ControllerBase {

  public function display($id) {
    $database = \Drupal::database();
    $query = $database->select('students', 's')
      ->fields('s')->condition('sid', $id, '=');
    $result = $query->execute()->fetch();
    if (!$result) {
      throw new NotFoundHttpException();
    }
    $rows = [
      ['label' => t('Name: '), 'student_name' => $result->student_name],
      ['label' => t('Enrollment Number: '), 'student_rollno' => $result->student_rollno],
      ['label' => t('Email: '), 'student_mail' => $result->student_mail],
      ['label' => t('Contact Number: '), 'student_phone' => $result->student_phone],
      ['label' => t('Date of Birth: '), 'student_dob' => date('Y-m-d', strtotime($result->student_dob))],
      ['label' => t('Gender: '), 'student_gender' => $result->student_gender],
      ['label' => t('Average Mark: '), 'student_average_mark' => $result->average_mark]

    ];
    $form['table'] = [
      '#type' => 'table',
      '#rows' => $rows,
      '#empty' => t('No students found'),
    ];
    $form['#cache'] = [
      'max-age' => 0,
    ];

    return $form;
  }

}
