<?php

/**
 * @file
 * Display students info in table format
 * Functionality of this Controller is wired to Drupal in student_registration.routing.yml
 */

namespace Drupal\student_registration\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Link;
use Drupal\Core\Url;

class StudentTableController extends ControllerBase {

  public function display() {
    //create table header
    $header_table = [
      'title' => t('Title'),
      'viewlink' => t('View'),
      'editlink' => t('Edit'),
      'deletelink' => t('Delete'),
    ];

    //select data from db
    $database = \Drupal::database();
    $query = $database->select('students', 's')
      ->fields('s', ['sid', 'student_name', 'student_mail']);
    $rows = [];
    $results = $query->execute()->fetchAll();
    foreach ($results as $row) {
      $view = Url::fromUserInput('/registration/' . $row->sid);
      $delete = Url::fromUserInput('/registration/' . $row->sid . '/delete');
      $edit = Url::fromUserInput('/registration/' . $row->sid . '/edit');

      //print the data from table
      $rows[] = [
        'title' => $row->student_name . ' ' . $row->student_mail,
        Link::fromTextAndUrl(t('View'), $view),
        Link::fromTextAndUrl(t('Edit'), $edit),
        Link::fromTextAndUrl(t('Delete'), $delete),
      ];
    }

    //display data
    $form['table'] = [
      '#type' => 'table',
      '#header' => $header_table,
      '#rows' => $rows,
      '#empty' => t('No students found'),
    ];
    $form['#cache'] = [
      'max-age' => 0,
    ];

    return $form;
  }

}
