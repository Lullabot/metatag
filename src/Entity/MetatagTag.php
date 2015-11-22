<?php

/**
 * @file
 * Contains \Drupal\metatag\Entity\MetatagTag.
 */

namespace Drupal\metatag\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;
use Drupal\metatag\MetatagTagInterface;

/**
 * Defines the Metatag tag entity.
 *
 * @ConfigEntityType(
 *   id = "metatag_tag",
 *   label = @Translation("Metatag tag"),
 *   handlers = {
 *     "list_builder" = "Drupal\metatag\MetatagTagListBuilder",
 *     "form" = {
 *       "add" = "Drupal\metatag\Form\MetatagTagForm",
 *       "edit" = "Drupal\metatag\Form\MetatagTagForm",
 *       "delete" = "Drupal\metatag\Form\MetatagTagDeleteForm"
 *     }
 *   },
 *   config_prefix = "metatag_tag",
 *   admin_permission = "administer site configuration",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/metatag_tag/{metatag_tag}",
 *     "edit-form" = "/admin/structure/metatag_tag/{metatag_tag}/edit",
 *     "delete-form" = "/admin/structure/metatag_tag/{metatag_tag}/delete",
 *     "collection" = "/admin/structure/visibility_group"
 *   }
 * )
 */
class MetatagTag extends ConfigEntityBase implements MetatagTagInterface {
  /**
   * The Metatag tag ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The Metatag tag label.
   *
   * @var string
   */
  protected $label;

  /**
   * The Metatag value.
   *
   * @var string
   */
  public $value;
}
