<?php

/**
 * @file
 * Contains \Drupal\metatag\Entity\MetatagDefaults.
 */

namespace Drupal\metatag\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;
use Drupal\metatag\MetatagDefaultsInterface;

/**
 * Defines the Metatag defaults entity.
 *
 * @ConfigEntityType(
 *   id = "metatag_defaults",
 *   label = @Translation("Metatag defaults"),
 *   handlers = {
 *     "list_builder" = "Drupal\metatag\MetatagDefaultsListBuilder",
 *     "form" = {
 *       "add" = "Drupal\metatag\Form\MetatagDefaultsForm",
 *       "edit" = "Drupal\metatag\Form\MetatagDefaultsForm",
 *     }
 *   },
 *   config_prefix = "metatag_defaults",
 *   admin_permission = "administer site configuration",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/metatag_defaults/{metatag_defaults}",
 *     "edit-form" = "/admin/structure/metatag_defaults/{metatag_defaults}/edit",
 *     "collection" = "/admin/structure/visibility_group"
 *   }
 * )
 */
class MetatagDefaults extends ConfigEntityBase implements MetatagDefaultsInterface {
  /**
   * The Metatag defaults ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The Metatag defaults label.
   *
   * @var string
   */
  protected $label;

  /**
   * The default tag values.
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
