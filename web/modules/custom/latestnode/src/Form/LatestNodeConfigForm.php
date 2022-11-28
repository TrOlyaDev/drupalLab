<?php

namespace Drupal\latestnode\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class LatestNodeConfigForm extends ConfigFormBase {

  /**
   * Config settings.
   *
   * @var string
   */
  const SETTINGS = 'latestnode.settings';

  /**
   * {@inheritDoc}
   */
  protected function getEditableConfigNames() {
    return [
      static::SETTINGS,
    ];
  }

  /**
   * {@inheritDoc}
   */
  public function getFormId() {
    return 'latestnode.settings_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config(static::SETTINGS);
    //select, node type list
    $form['node_type'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Node Type'),
      '#default_value' => $config->get('node_type'),
    ];

    $form['node_count'] = [
      '#type' => 'number',
      '#title' => $this->t('Node Count'),
      '#default_value' => $config->get('node_count'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Retrieve the configuration.
    $this->config(static::SETTINGS)
      ->set('node_type', $form_state->getValue('node_type'))
      ->set('node_count', $form_state->getValue('node_count'))
      ->save();

    parent::submitForm($form, $form_state);
  }

}
