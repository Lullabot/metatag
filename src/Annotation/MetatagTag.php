<?php
/**
 * @file
 * Contains the \Drupal\metatag\Annotation\MetatagTag annotation plugin.
 */

namespace Drupal\metatag\Annotation;

use Drupal\Component\Annotation\Plugin;


/**
 * Defines a MetatagTag annotation object.
 *
 * @Annotation
 */
class MetatagTag extends Plugin {

  /**
   * The meta tag plugin's internal ID, in machine name format.
   *
   * @var string
   */
  public $id;

  /**
   * The display label/name of the meta tag plugin.
   *
   * @var \Drupal\Core\Annotation\Translation
   *
   * @ingroup plugin_translatable
   */
  public $label;

  /**
   * A longer explanation of what the field is for.
   *
   * @var \Drupal\Core\Annotation\Translation
   *
   * @ingroup plugin_translatable
   */
  public $description;

  /**
   * Proper name of the actual meta tag itself.
   *
   * @var string
   */
  public $name;

}
