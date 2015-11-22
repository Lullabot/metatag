<?php

/**
 * @file
 * Contains \Drupal\metatag\Form\MetatagTagForm.
 */

namespace Drupal\metatag\Form;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class MetatagTagForm.
 *
 * @package Drupal\metatag\Form
 */
class MetatagTagForm extends EntityForm {
  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    $metatag_tag = $this->entity;
    $form['label'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $metatag_tag->label(),
      '#description' => $this->t("Label for the Metatag tag."),
      '#required' => TRUE,
    );

    $form['id'] = array(
      '#type' => 'machine_name',
      '#default_value' => $metatag_tag->id(),
      '#machine_name' => array(
        'exists' => '\Drupal\metatag\Entity\MetatagTag::load',
      ),
      '#disabled' => !$metatag_tag->isNew(),
    );

    /* You will need additional form elements for your custom properties. */

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $metatag_tag = $this->entity;
    $status = $metatag_tag->save();

    switch ($status) {
      case SAVED_NEW:
        drupal_set_message($this->t('Created the %label Metatag tag.', [
          '%label' => $metatag_tag->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label Metatag tag.', [
          '%label' => $metatag_tag->label(),
        ]));
    }
    $form_state->setRedirectUrl($metatag_tag->urlInfo('collection'));
  }

}
