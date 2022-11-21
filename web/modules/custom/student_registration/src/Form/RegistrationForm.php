<?php

/**
 * @file
 * Form for registration new students
 * This form is wired to Drupal in registration_students.routing.yml
 */

namespace Drupal\student_registration\Form;

use Drupal\Component\Utility\EmailValidator;
use Drupal\Core\Database\Connection;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

class RegistrationForm extends FormBase {

  /**
   * Database connection
   * @var \Drupal\Core\Database\Connection
   */
  protected $database;

  /**
   * Email validator
   * @var \Drupal\Component\Utility\EmailValidator
   */
  protected $emailValidator;

  public function __construct(Connection $database, EmailValidator $emailValidator) {
    $this->database = $database;
    $this->emailValidator = $emailValidator;
  }

  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('database'),
      $container->get('email.validator')
    );
  }

  /**
   * {@inheritDoc}
   */
  public function getFormId() {
    return 'student_registration.student_registration_form';
  }

  /**
   * {@inheritDoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $sid = NULL) {
    if ($sid) {
      $query = $this->database->select('students', 's')
        ->fields('s')
        ->condition('sid', $sid)
        ->execute();
      $data = $query->fetch();
    }
    $form['sid'] = [
      '#type' => 'hidden',
      '#value' => ($sid) ? $sid : '',
    ];
    $form['student_name'] = [
      '#type' => 'textfield',
      '#title' => t('Enter Name:'),
      '#required' => TRUE,
      '#default_value' => ($sid && isset($data->student_name)) ? $data->student_name : '',
    ];
    $form['student_rollno'] = [
      '#type' => 'textfield',
      '#title' => t('Enter Enrollment Number:'),
      '#required' => TRUE,
      '#default_value' => ($sid && isset($data->student_rollno)) ? $data->student_rollno : '',
    ];
    $form['student_mail'] = [
      '#type' => 'email',
      '#title' => t('Enter Email ID:'),
      '#required' => TRUE,
      '#default_value' => ($sid && isset($data->student_mail)) ? $data->student_mail : '',
    ];
    $form['student_phone'] = [
      '#type' => 'tel',
      '#title' => t('Enter Contact Number'),
      '#default_value' => ($sid && isset($data->student_phone)) ? $data->student_phone : '',
    ];
    $form['student_dob'] = [
      '#type' => 'date',
      '#title' => t('Enter DOB:'),
      '#required' => TRUE,
      '#default_value' => ($sid && isset($data->student_dob)) ? date('Y-m-d', strtotime($data->student_dob)) : '',
    ];
    $form['student_gender'] = [
      '#type' => 'select',
      '#title' => t('Select Gender:'),
      '#options' => [
        'Male' => t('Male'),
        'Female' => t('Female'),
        'Other' => t('Other'),
      ],
      '#default_value' => ($sid && isset($data->student_gender)) ? $data->student_gender : '',
    ];
    $form['average_mark'] = [
      '#type' => 'number',
      '#step' => 0.01,
      '#title' => t('Average Mark'),
      '#required' => TRUE,
      '#default_value' => ($sid && isset($data->average_mark)) ? $data->average_mark : '',
    ];
    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => ($sid && isset($data) && $data) ? $this->t('Update') : $this->t('Register'),
      '#button_type' => 'primary',
    ];
    return $form;
  }

  /**
   * {@inheritDoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    if (strlen($form_state->getValue('student_rollno')) < 8) {
      $form_state->setErrorByName('student_rollno', $this->t('Please enter a valid Enrollment Number'));
    }
    if (strlen($form_state->getValue('student_phone')) < 10) {
      $form_state->setErrorByName('student_phone', $this->t('Please enter a valid Contact Number'));
    }
    $studentMail = $form_state->getValue('student_mail');
    if ($studentMail == !$this->emailValidator->isValid($studentMail)) {
      $form_state->setErrorByName(
        'email',
        t('The email address %mail is not valid.', array('%mail' => $studentMail)));
    }
    if ($form_state->getValue('average_mark') > 100) {
      $form_state->setErrorByName('average_mark', $this->t('Please enter a valid Average Mark'));
    }
  }

  /**
   * {@inheritDoc}
   * @throws \Exception
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $formData = $form_state->getValues();
    unset($formData['submit'], $formData['op']);
    unset($formData['form_build_id'], $formData['form_token'], $formData['form_id']);
    $sid = $formData['sid'];
    unset($formData['sid']);

    if ($sid) {
      $query = $this->database->update('students')
        ->fields($formData)
        ->condition('sid', $sid)
        ->execute();
    } else {
      $query = $this->database->insert('students')
        ->fields($formData);
      $sid = $query->execute();
    }
    (new RedirectResponse('/registration/' . $sid))->send();
    exit(1);
  }

}
