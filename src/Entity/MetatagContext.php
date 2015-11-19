<?php

/**
 * @file
 * Contains \Drupal\metatag\Entity\MetatagContext.
 */

namespace Drupal\metatag\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;
use Drupal\metatag\MetatagContextInterface;

/**
 * Defines the Metatag context entity.
 *
 * @ConfigEntityType(
 *   id = "metatag_context",
 *   label = @Translation("Metatag context"),
 *   handlers = {
 *     "list_builder" = "Drupal\metatag\MetatagContextListBuilder",
 *     "form" = {
 *       "add" = "Drupal\metatag\Form\MetatagContextForm",
 *       "edit" = "Drupal\metatag\Form\MetatagContextForm",
 *       "delete" = "Drupal\metatag\Form\MetatagContextDeleteForm"
 *     }
 *   },
 *   config_prefix = "metatag_context",
 *   admin_permission = "administer site configuration",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/metatag_context/{metatag_context}",
 *     "edit-form" = "/admin/structure/metatag_context/{metatag_context}/edit",
 *     "delete-form" = "/admin/structure/metatag_context/{metatag_context}/delete",
 *     "collection" = "/admin/structure/visibility_group"
 *   }
 * )
 */
class MetatagContext extends ConfigEntityBase implements MetatagContextInterface {
  /**
   * The Metatag context ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The Metatag context label.
   *
   * @var string
   */
  protected $label;

  /**
   * The Metatag context title.
   *
   * @var string
   */
  protected $title;

  /**
   * The Metatag context description.
   *
   * @var string
   */
  protected $description;

  /**
   * The Metatag context abstract.
   *
   * @var string
   */
  protected $abstract;

  /**
   * The Metatag context keywords.
   *
   * @var string
   */
  protected $keywords;

}
