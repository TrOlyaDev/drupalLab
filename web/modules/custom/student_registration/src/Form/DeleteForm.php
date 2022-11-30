<?php

namespace Drupal\student_registration\Form;

use Drupal\Core\Database\Connection;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Form for confirmation student delete.
 */
class DeleteForm extends ConfirmFormBase {

  /**
   * Student id.
   *
   * @var int
   */
  public $sid;

  /**
   * Database connection.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $database;

  /**
   * Create an instance of DeleteForm.
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
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'student_registration.delete_form';
  }

  /**
   * {@inheritDoc}
   */
  public function getQuestion() {
    return $this->t('Do you want to delete student with id=%sid?', ['%sid' => $this->sid]);
  }

  /**
   * {@inheritDoc}
   */
  public function getCancelUrl() {
    return new Url('student_registration.registrations');
  }

  /**
   * {@inheritdoc}
   */
  public function getConfirmText() {
    return $this->t('Delete');
  }

  /**
   * {@inheritdoc}
   */
  public function getCancelText() {
    return $this->t('Cancel');
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $sid = NULL) {
    $this->sid = $sid;
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->database->delete('students')
      ->condition('sid', $this->sid, '=')
      ->execute();
    $this->messenger->addMessage("succesfully deleted");
    $form_state->setRedirect('student_registration.registrations');
  }

}
