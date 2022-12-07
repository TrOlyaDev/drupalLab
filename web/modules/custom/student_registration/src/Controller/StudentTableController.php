<?php

namespace Drupal\student_registration\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Connection;
use Drupal\Core\Link;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Controller to display students info in table format.
 */
class StudentTableController extends ControllerBase {

  /**
   * Database connection.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $database;

  /**
   * Create an instance of StudentTableController.
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
   * Display the students info table.
   *
   * @return array
   *   Table header.
   */
  public function display() {
    // Create table header.
    $header_table = [
      'title' => $this->t('Title'),
      'viewlink' => $this->t('View'),
      'editlink' => $this->t('Edit'),
      'deletelink' => $this->t('Delete'),
    ];

    // Select data from db.
    $query = $this->database->select('students', 's')
      ->fields('s', ['sid', 'student_name', 'student_mail']);
    $rows = [];
    $results = $query->execute()->fetchAll();
    foreach ($results as $row) {
      $view = Url::fromUserInput('/registration/' . $row->sid);
      $delete = Url::fromUserInput('/registration/' . $row->sid . '/delete');
      $edit = Url::fromUserInput('/registration/' . $row->sid . '/edit');

      // Print the data from table.
      $rows[] = [
        'title' => $row->student_name . ' ' . $row->student_mail,
        Link::fromTextAndUrl($this->t('View'), $view),
        Link::fromTextAndUrl($this->t('Edit'), $edit),
        Link::fromTextAndUrl($this->t('Delete'), $delete),
      ];
    }

    // Display data.
    $form['table'] = [
      '#type' => 'table',
      '#header' => $header_table,
      '#rows' => $rows,
      '#empty' => $this->t('No students found'),
    ];
    $form['#cache'] = [
      'max-age' => 0,
    ];

    return $form;
  }

}
