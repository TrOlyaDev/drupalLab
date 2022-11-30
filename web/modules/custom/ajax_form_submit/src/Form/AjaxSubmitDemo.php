<?php

namespace Drupal\ajax_form_submit\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;

/**
 * Simple ajax form, that can add two integers and display the sum.
 */
class AjaxSubmitDemo extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'ajax_form_submit.ajax_submit_demo';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['message'] = [
      '#type' => 'markup',
      '#markup' => '<div class="result_message"></div>',
    ];

    $form['number_1'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Number 1'),
    ];

    $form['number_2'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Number 2'),
    ];

    $form['actions'] = [
      '#type' => 'button',
      '#value' => $this->t('Submit'),
      '#ajax' => [
        'callback' => '::setMessage',
      ],
    ];

    return $form;
  }

  /**
   * Returns message with sum of two arguments.
   *
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *
   * @return \Drupal\Core\Ajax\AjaxResponse
   */
  public function setMessage(array $form, FormStateInterface $form_state) {
    $response = new AjaxResponse();
    $response->addCommand(
      new HtmlCommand(
        '.result_message',
        '<div class="my_top_message">' . $this->t('The results is @result', ['@result' => ($form_state->getValue('number_1') + $form_state->getValue('number_2'))]) . '</div>'),
    );
    return $response;
  }

  /**
   * {@inheritDoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // @todo Implement submitForm() method.
  }

}
