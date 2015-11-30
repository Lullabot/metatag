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

    $form += \Drupal::service('metatag.token')->tokenBrowser();

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
    drupal_set_message($this->t('Saved the %label Metatag defaults.', [
      '%label' => $metatag_defaults->label(),
    ]));

    $form_state->setRedirectUrl($metatag_defaults->urlInfo('collection'));
  }

}
