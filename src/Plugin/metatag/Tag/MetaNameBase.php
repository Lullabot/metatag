<?php
/**
 * @file
 * Contains \Drupal\metatag\Plugin\metatag\Tag\MetaNameBase.
 */

/**
 * Each meta tag will extend this base.
 */

namespace Drupal\metatag\Plugin\metatag\Tag;

use Drupal\Component\Plugin\PluginBase;
use Drupal\Core\Form\FormStateInterface;

abstract class MetaNameBase extends PluginBase {
  /**
   * Machine name of the meta tag plugin.
   *
   * @var string
   */
  protected $id;

  /**
   * Official metatag name.
   *
   * @var string
   */
  protected $name;

  /**
   * The title of the plugin.
   *
   * @var \Drupal\Core\Annotation\Translation
   *
   * @ingroup plugin_translatable
   */
  protected $label;

  /**
   * A longer explanation of what the field is for.
   *
   * @var \Drupal\Core\Annotation\Translation
   *
   * @ingroup plugin_translatable
   */
  protected $description;

  /**
   * The value of the metatag in this instance.
   *
   * @var mixed
   */
  protected $value;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, array $plugin_definition) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);

    // Set the properties from the annotation.
    // @TODO: Should we have setProperty() methods for each of these?
    $this->id = $plugin_definition['id'];
    $this->name = $plugin_definition['name'];
    $this->label = $plugin_definition['label'];
    $this->description = $plugin_definition['description'];
  }

  public function id() {
    return $this->id;
  }
  public function label() {
    return $this->label;
  }
  public function description() {
    return $this->description;
  }
  public function name() {
    return $this->name;
  }

  /**
   * Generate a form element for this meta tag.
   */
  public function form() {
    $form = array(
      '#type' => 'textfield',
      '#title' => $this->label(),
      '#default_value' => $this->value(),
      '#maxlength' => 255,
      '#required' => FALSE,
      '#description' => $this->description(),
      '#element_validate' => array(array(get_class($this), 'validateTag')),
    );

    return $form;
  }

  /**
   * Returns the value for this tag.
   *
   * @return
   *   string the value of this tag.
   */
  public function value() {
    return $this->value;
  }

  /**
   * Sets the value of this tag.
   *
   * @param string $value
   *   The value to set to this tag.
   */
  public function setValue($value) {
    $this->value = $value;
  }

  /**
   * Renders this tag.
   *
   * @return
   *   The HTML that represents this tag.
   */
  public function render() {
    if (empty($this->value)) {
      // If there is no value, we don't want a tag output.
      $element = '';
    }
    else {
      $processed_value = \Drupal::token()->replace($this->value);
      $element = array(
        '#tag' => 'meta',
        '#attributes' => array(
          'name' => $this->name,
          'content' => $processed_value,
        )
      );
    }

    return $element;
  }

  /**
   * Validates the metatag data.
   *
   * @param array $element
   *   The form element to process.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The form state.
   */
  public static function validateTag(array &$element, FormStateInterface $form_state) {
    //@TODO: If there is some common validation, put it here. Otherwise, make it abstract?
  }

}
