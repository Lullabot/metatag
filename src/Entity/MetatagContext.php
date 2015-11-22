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
   * The list of tag values for this context.
   *
   * @var array
   */
  protected $tags = [];

  /**
   * Returns TRUE if a tag exists.
   *
   * @param string $tag_id
   *   The identifier of the tag.
   * @return boolean
   *   TRUE if the tag exists.
   */
  public function hasTag($tag_id) {
    return array_key_exists($tag_id, $this->tags);
  }

  /**
   * Returns the value of a tag.
   *
   * @param string $tag_id
   *   The identifier of the tag.
   * @return array|NULL
   *   array containing the tag values or NULL if not found.
   */
  public function getTag($tag_id) {
    if (!$this->hasTag($tag_id)) {
      return NULL;
    }
    return $this->tags[$tag_id];
  }
}
