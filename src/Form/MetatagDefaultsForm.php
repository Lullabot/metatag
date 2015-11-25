<?php

/**
 * @file
 * Contains \Drupal\metatag\Form\MetatagDefaultsForm.
 */

namespace Drupal\metatag\Form;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;
use Drupal\metatag\Entity\MetatagTag;

/**
 * Class MetatagDefaultsForm.
 *
 * @package Drupal\metatag\Form
 */
class MetatagDefaultsForm extends EntityForm {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    $metatag_defaults = $this->entity;

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

    // If this is a new Metatag defaults, then list available bundles.
    if ($metatag_defaults->isNew()) {
      $options = $this->getAvailableBundles();
      $form['id'] = array(
        '#type' => 'select',
        '#title' => t('Type'),
        '#description' => t('Select the type of default meta tags you would like to add.'),
        '#options' => $options,
        '#required' => TRUE,
      );
    }

    // Load all tag plugins and render their form representation.
    $tag_manager = \Drupal::service('plugin.manager.metatag.tag');
    $tags = $tag_manager->getDefinitions();
    foreach ($tags as $tag_id => $tag_definition) {
      $tag = $tag_manager->createInstance($tag_id);
      // If the config_entity has a value for this tag, set it.
      if ($metatag_defaults->hasTag($tag_id)) {
        $tag->setValue($metatag_defaults->getTag($tag_id));
      }
      $form[$tag_id] = $tag->form();
    }

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $metatag_defaults = $this->entity;

    // Set the label on new defaults.
    if ($metatag_defaults->isNew()) {
      $metatag_defaults_id = $form_state->getValue('id');
      list($entity_type, $entity_bundle) = explode('__', $metatag_defaults_id);
      // Get the entity label.
      $entity_manager = \Drupal::service('entity.manager');
      $entity_info = $entity_manager->getDefinitions();
      $entity_label = (string) $entity_info[$entity_type]->get('label');
      // Get the bundle label.
      $bundle_info = $entity_manager->getBundleInfo($entity_type);
      $bundle_label = $bundle_info[$entity_bundle]['label'];
      // Set the label to the config entity.
      $this->entity->set('label', $entity_label . ': ' . $bundle_label);
    }

    // Set tags within the Metatag entity.
    $tag_manager = \Drupal::service('plugin.manager.metatag.tag');
    $tags = $tag_manager->getDefinitions();
    $tag_values = array();
    foreach ($tags as $tag_id => $tag_definition) {
      if ($form_state->hasValue($tag_id)) {
        // Some plugins need to process form input before storing it.
        // Hence, we set it and then get it.
        $tag = $tag_manager->createInstance($tag_id);
        $tag->setValue($form_state->getValue($tag_id));
        if (!empty($tag->value())) {
          $tag_values[$tag_id] = $tag->value();
        }
      }
    }
    $metatag_defaults->set('tags', $tag_values);
    $status = $metatag_defaults->save();

    switch ($status) {
      case SAVED_NEW:
        drupal_set_message($this->t('Created the %label Metatag defaults.', [
          '%label' => $metatag_defaults->label(),
        ]));
        break;
      default:
        drupal_set_message($this->t('Saved the %label Metatag defaults.', [
          '%label' => $metatag_defaults->label(),
        ]));
    }

    $form_state->setRedirectUrl($metatag_defaults->urlInfo('collection'));
  }

  /**
   * Returns an array of available bundles to override.
   *
   * @return array
   *   A list of available bundles as $id => $label.
   */
  protected function getAvailableBundles() {
    $options = array();
    // @TODO discover supported entities.
    $entity_types = array(
      'node' => 'Node',
      'taxonomy_term' => 'Taxonomy term',
    );
    $entity_manager = \Drupal::service('entity.manager');
    foreach ($entity_types as $entity_type => $entity_label) {
      $bundles = $entity_manager->getBundleInfo($entity_type);
      foreach ($bundles as $bundle_id => $bundle_metadata) {
        $options[$entity_label][$entity_type . '__' . $bundle_id] = $bundle_metadata['label'];
      }
    }
    return $options;
  }

}
