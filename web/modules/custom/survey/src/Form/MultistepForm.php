<?php

/**
 * @file
 * Multistep Ajax Form
 * This form is wired to Drupal in registration_students.routing.yml
 */

namespace Drupal\survey\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form for the multistep survey
 */
class MultistepForm extends FormBase {

  /**
   * {@inheritDoc}
   */
  public function getFormId() {
    return 'survey.multistep_form';
  }

  /**
   * {@inheritDoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    //current step
    $step = $form_state->get('step') ?? 1;

    //step titles
    $step_titles = [
      $this->t('Personal Data'),
      $this->t('Parameters'),
      $this->t('Survey'),
    ];

    //form title
    $form['title'] = [
      '#type' => 'item',
      '#title' => $this->t('Step :step from 3: :title_form - :title_step', [
        ':step' => $step,
        ':title_form' => $this->t('MultistepForm'),
        ':title_step' => $step_titles[$step - 1],
      ]),
    ];

    //form fields
    //step 1 fields
    $form['step1']['name'] = [
      '#type' => 'textfield',
      '#title' => t('Name:'),
      '#default_value' => $form_state->getValue('name'),
      '#access' => $step === 1,
    ];
    $form['step1']['surname'] = [
      '#type' => 'textfield',
      '#title' => t('Surname:'),
      '#default_value' => $form_state->getValue('surname'),
      '#access' => $step === 1,
    ];
    //step 2 fields
    $form['step2']['age'] = [
      '#type' => 'select',
      '#title' => $this->t('Age:'),
      '#options' => [
        'under18' => $this->t('Under 18'),
        '18-25' => '18-25',
        '25-35' => '25-30',
        'over35' => $this->t('Over 35'),
      ],
      '#default_value' => $form_state->getValue('age') ?? $form_state->get(['data', 'age']) ?? '18-25',
      '#access' => $step === 2,
    ];
    $form['step2']['gender'] = [
      '#type' => 'radios',
      '#title' => $this->t('Gender:'),
      '#options' => [
        'male' => $this->t('Male'),
        'female' => $this->t('Female'),
        'other' => $this->t('Other'),
      ],
      '#default_value' => $form_state->getValue('gender') ?? $form_state->get(['data', 'gender']),
      '#access' => $step === 2,
    ];
    //step 3 fields
    $form['step3']['interests'] = [
      '#type' => 'checkboxes',
      '#title' => $this->t('Interests:'),
      '#options' => [
        'chess' => $this->t('Chess'),
        'football' => $this->t('Football'),
        'politics' => $this->t('Politics'),
        'gardening' => $this->t('Gardening'),
      ],
      '#default_value' => $form_state->getValue('interests') ?? $form_state->get(['data', 'interests']) ?? [],
      '#access' => $step === 3,
    ];
    $form['step3']['dreams'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Dreams:'),
      '#rows' => 10,
      '#default_value' => $form_state->getValue('dreams') ?? $form_state->get(['data', 'dreams']),
      '#access' => $step ===3,
    ];

    // Submit buttons.
    $form['actions']['#type'] = 'actions';
    $form['actions']['prev'] = [
      '#type' => 'submit',
      '#value' => $this->t('Prev'),
      '#submit' => ['::prevSubmit'],
      '#limit_validation_errors' => [],
      '#access' => $step > 1,
      '#ajax' => [
        'callback' => '::myAjaxCallback',
        'wrapper' => 'survey-ajax-wrapper',
      ],
    ];
    $form['actions']['next'] = [
      '#type' => 'submit',
      '#value' => $this->t('Next'),
      '#submit' => ['::nextSubmit'],
      '#access' => $step < 3,
      '#ajax' => [
        'callback' => '::myAjaxCallback',
        'wrapper' => 'survey-ajax-wrapper',
      ],
    ];
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
      '#access' => $step == 3,
      '#ajax' => [
        'callback' => '::myAjaxCallback',
        'wrapper' => 'survey-ajax-wrapper',
      ],
    ];

    // Wrapper for ajax callback
    $form['#prefix'] = '<div id="survey-ajax-wrapper">';
    $form['#suffix'] = '</div>';

    $form['#tree'] = TRUE;
    return $form;
  }

  /**
   * Returns form
   *
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *
   * @return array
   */
  public function myAjaxCallback(array &$form, FormStateInterface $form_state) {
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    // Additional form validation put here.
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    //save data
    $values = $form_state->getValues();
    $data = $form_state->get('data') ?? [];
    $data = array_merge($data, $values['step3']);

    //message with input data
    foreach ($data as $key => $value) {
      $this->messenger()->addStatus($this->t(':key : :value', [
        ':key' => $key,
        ':value' => is_array($value) ? implode(', ', array_filter($value)) : $value,
      ]));
    }

    //clean form
    $form_state->set('data', []);
    $form_state->set('step', 1);
    $form_state->setRebuild(TRUE);
  }

  /**
   * Move to the next step of the survey
   *
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *
   * @return void
   */
  public function nextSubmit(array &$form, FormStateInterface $form_state) {
    $step = $form_state->get('step') ?? 1;

    //save data
    $values = $form_state->getValues();
    $data = $form_state->get('data') ?? [];
    $form_state->set('data', array_merge($data, $values['step' . $step]));

    //next step
    $form_state->set('step', ++$step);
    $form_state->setRebuild(TRUE);
  }

  /**
   * Move to the previous step of the survey
   *
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *
   * @return void
   */
  public function prevSubmit(array &$form, FormStateInterface $form_state) {
    $step = $form_state->get('step') ?? 1;

    //save data
    $values = $form_state->getUserInput();
    $data = $form_state->get('data') ?? [];
    $form_state->set('data', array_merge($data, $values['step' . $step]));

    // Restore step data.
    $form_state->setValues($data);

    //previous step
    $form_state->set('step', --$step);
    $form_state->setRebuild(TRUE);
  }

}
