<?php

/**
 * @file
 * Contains \Drupal\metatag\Entity\MetatagConfig.
 */

namespace Drupal\metatag\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;
use Drupal\metatag\MetatagConfigInterface;

/**
 * Defines the Metatag config entity.
 *
 * @ConfigEntityType(
 *   id = "metatag_config",
 *   label = @Translation("Metatag config"),
 *   handlers = {
 *     "list_builder" = "Drupal\metatag\MetatagConfigListBuilder",
 *     "form" = {
 *       "add" = "Drupal\metatag\Form\MetatagConfigForm",
 *       "edit" = "Drupal\metatag\Form\MetatagConfigForm",
 *       "delete" = "Drupal\metatag\Form\MetatagConfigDeleteForm"
 *     }
 *   },
 *   config_prefix = "metatag_config",
 *   admin_permission = "administer site configuration",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/metatag_config/{metatag_config}",
 *     "edit-form" = "/admin/structure/metatag_config/{metatag_config}/edit",
 *     "delete-form" = "/admin/structure/metatag_config/{metatag_config}/delete",
 *     "collection" = "/admin/structure/visibility_group"
 *   }
 * )
 */
class MetatagConfig extends ConfigEntityBase implements MetatagConfigInterface {
  /**
   * The Metatag config ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The Metatag config label.
   *
   * @var string
   */
  protected $label;

  /**
   * The Metatag title.
   *
   * @var string
   */
  protected $title;

  /**
   * The Metatag description.
   *
   * @var string
   */
  protected $description;

  /**
   * The Metatag abstract.
   *
   * @var string
   */
  protected $abstract;

  /**
   * The Metatag keywords.
   *
   * @var string
   */
  protected $keywords;
}
