<?php

namespace Drupal\mymodule\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Form for user data
 */
class AddUserForm extends FormBase {

  /**
   * {@inheritDoc}
   */
  public function getFormId() {
    return 'mymodule_adduserform';
  }

  /**
   * {@inheritDoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Name'),
      '#description' => $this->t('User name'),
    ];
    $form['email'] = [
      '#type' => 'email',
      '#title' => $this->t('Email'),
      '#description' => $this->t('User email'),
    ];
    $form['age'] = [
      '#type' => 'number',
      '#title' => $this->t('Age'),
      '#description' => $this->t('User age'),
    ];
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Add'),
    ];

    return $form;
  }

  /**
   * {@inheritDoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $url = '/successaddeduser/' . $form['name']['#value'] . '/' . $form['email']['#value'] . '/' . $form['age']['#value'];
    (new RedirectResponse($url))->send();
    exit(1);
  }

}
