<?php

/**
 * @file
 * Contains \Drupal\metatag\Form\MetatagContextForm.
 */

namespace Drupal\metatag\Form;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;
use Drupal\metatag\Entity\MetatagTag;

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

    // Load all tag plugins and render their form representation.
    $tag_manager = \Drupal::service('plugin.manager.metatag.tag');
    $tags = $tag_manager->getDefinitions();
    foreach ($tags as $tag_id => $tag_definition) {
      $tag = $tag_manager->createInstance($tag_id);
      // If the context has a value for this tag, set it.
      if ($metatag_context->hasTag($tag_id)) {
        $tag->setValue($metatag_context->getTag($tag_id));
      }
      $form[$tag_id] = $tag->form();
    }

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $metatag_context = $this->entity;

    // Set tags within the Metatag entity.
    $tag_manager = \Drupal::service('plugin.manager.metatag.tag');
    $tags = $tag_manager->getDefinitions();
    $tag_values = array();
    foreach ($tags as $tag_id => $tag_definition) {
      if ($form_state->hasValue($tag_id)) {
        $tag_values[$tag_id] = $form_state->getValue($tag_id);
      }
    }
    $metatag_context->set('tags', $tag_values);
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
