<?php

/**
 * @file
 * Contains \Drupal\metatag\Form\MetatagContextForm.
 */

namespace Drupal\metatag\Form;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class MetatagContextForm.
 *
 * @package Drupal\metatag\Form
 */
class MetatagContextForm extends EntityForm {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    $metatag_context = $this->entity;

    // Add the token list to the top of the fieldset.
    $form['tokens'] = array(
      '#theme' => 'token_tree',
      '#token_types' => array(),
      '#global_types' => TRUE,
      '#click_insert' => TRUE,
      '#show_restricted' => FALSE,
      '#recursion_limit' => 3,
      '#dialog' => TRUE,
    );

    $form['intro_text'] = array(
      '#markup' => '<p>' . t('Configure the meta tags below. Use tokens (see the "Browse available tokens" popup) to avoid redundant meta data and search engine penalization. For example, a \'keyword\' value of "example" will be shown on all content using this configuration, whereas using the [node:field_keywords] automatically inserts the "keywords" values from the current entity (node, term, etc).') . '</p>',
    );

    $form['title'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Page title'),
      '#maxlength' => 255,
      '#default_value' => $metatag_context->get('title'),
      '#description' => $this->t("The text to display in the title bar of a visitor's web browser when they view this page. This meta tag may also be used as the title of the page when a visitor bookmarks or favorites this page."),
    );

    $form['description'] = array(
      '#type' => 'textarea',
      '#title' => $this->t('Description'),
      '#default_value' => $metatag_context->get('description'),
      '#description' => $this->t("A brief and concise summary of the page's content, preferably 150 characters or less. The description meta tag may be used by search engines to display a snippet about the page in search results."),
    );

    $form['abstract'] = array(
      '#type' => 'textarea',
      '#title' => $this->t('Abstract'),
      '#default_value' => $metatag_context->get('abstract'),
      '#description' => $this->t("A brief and concise summary of the page's content, preferably 150 characters or less. The abstract meta tag may be used by search engines for archiving purposes."),
    );

    $form['keywords'] = array(
      '#type' => 'textfield',
      '#maxlength' => 255,
      '#title' => $this->t('Keywords'),
      '#default_value' => $metatag_context->get('keywords'),
      '#description' => $this->t("A comma-separated list of keywords about the page. This meta tag is not supported by most search engines anymore."),
    );

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $metatag_context = $this->entity;
    $status = $metatag_context->save();

    switch ($status) {
      case SAVED_NEW:
        drupal_set_message($this->t('Created the %label Metatag context.', [
          '%label' => $metatag_context->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label Metatag context.', [
          '%label' => $metatag_context->label(),
        ]));
    }
    $form_state->setRedirectUrl($metatag_context->urlInfo('collection'));
  }

}
