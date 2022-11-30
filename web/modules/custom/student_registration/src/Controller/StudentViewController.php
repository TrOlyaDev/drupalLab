<?php

namespace Drupal\student_registration\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Connection;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Controller to display student info.
 */
class StudentViewController extends ControllerBase {

  /**
   * Database connection.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $database;

  /**
   * Create an instance of StudentViewController.
   *
   * @param \Drupal\Core\Database\Connection $database
   *   Database connection.
   */
  public function __construct(Connection $database) {
    $this->database = $database;
  }

  /**
   * {@inheritDoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('database')
    );
  }

  /**
   * Display the student info.
   *
   * @param int $id
   *   Student ID.
   *
   * @return array
   *   Array contains information about student to display.
   */
  public function display(int $id): array {
    $query = $this->database->select('students', 's')
      ->fields('s')->condition('sid', $id, '=');
    $result = $query->execute()->fetch();
    if (!$result) {
      throw new NotFoundHttpException();
    }
    $rows = [
      ['label' => $this->t('Name:'), 'student_name' => $result->student_name],
      [
        'label' => $this->t('Enrollment Number:'),
        'student_rollno' => $result->student_rollno,
      ],
      ['label' => $this->t('Email:'), 'student_mail' => $result->student_mail],
      [
        'label' => $this->t('Contact Number:'),
        'student_phone' => $result->student_phone,
      ],
      [
        'label' => $this->t('Date of Birth:'),
        'student_dob' => date('Y-m-d', strtotime($result->student_dob)),
      ],
      [
        'label' => $this->t('Gender:'),
        'student_gender' => $result->student_gender,
      ],
      [
        'label' => $this->t('Average Mark:'),
        'student_average_mark' => $result->average_mark,
      ],

    ];
    $form['table'] = [
      '#type' => 'table',
      '#rows' => $rows,
      '#empty' => $this->t('No students found'),
    ];
    $form['#cache'] = [
      'max-age' => 0,
    ];

    return $form;
  }

}
