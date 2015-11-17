<?php

/**
 * @file
 * Contains \Drupal\metatag\Form\MetatagConfigForm.
 */

namespace Drupal\metatag\Form;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class MetatagConfigForm.
 *
 * @package Drupal\metatag\Form
 */
class MetatagConfigForm extends EntityForm {
  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    $metatag_config = $this->entity;
    $a = 'b';
    $form['label'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $metatag_config->label(),
      '#description' => $this->t("Label for the Metatag config."),
      '#required' => TRUE,
    );

    $form['id'] = array(
      '#type' => 'machine_name',
      '#default_value' => $metatag_config->id(),
      '#machine_name' => array(
        'exists' => '\Drupal\metatag\Entity\MetatagConfig::load',
      ),
      '#disabled' => !$metatag_config->isNew(),
    );

    $form['title'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Page title'),
      '#maxlength' => 255,
      '#default_value' => $metatag_config->get('title') ? $metatag_config->get('title') : '[current-page:title] | [site:name]',
      '#description' => $this->t("The text to display in the title bar of a visitor's web browser when they view this page. This meta tag may also be used as the title of the page when a visitor bookmarks or favorites this page."),
    );

    $form['description'] = array(
      '#type' => 'textarea',
      '#title' => $this->t('Description'),
      '#default_value' => $metatag_config->get('description'),
      '#description' => $this->t("A brief and concise summary of the page's content, preferably 150 characters or less. The description meta tag may be used by search engines to display a snippet about the page in search results."),
    );

    $form['abstract'] = array(
      '#type' => 'textarea',
      '#title' => $this->t('Abstract'),
      '#default_value' => $metatag_config->get('abstract'),
      '#description' => $this->t("A brief and concise summary of the page's content, preferably 150 characters or less. The abstract meta tag may be used by search engines for archiving purposes."),
    );

    $form['keywords'] = array(
      '#type' => 'textfield',
      '#maxlength' => 255,
      '#title' => $this->t('Keywords'),
      '#default_value' => $metatag_config->get('keywords'),
      '#description' => $this->t("A comma-separated list of keywords about the page. This meta tag is not supported by most search engines anymore."),
    );

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $metatag_config = $this->entity;
    $status = $metatag_config->save();

    switch ($status) {
      case SAVED_NEW:
        drupal_set_message($this->t('Created the %label Metatag config.', [
          '%label' => $metatag_config->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label Metatag config.', [
          '%label' => $metatag_config->label(),
        ]));
    }
    $form_state->setRedirectUrl($metatag_config->urlInfo('collection'));
  }

}
