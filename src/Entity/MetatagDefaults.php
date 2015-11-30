<?php

/**
 * @file
 * Contains \Drupal\metatag\Entity\MetatagDefaults.
 */

namespace Drupal\metatag\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;
use Drupal\Core\Config\FileStorage;
use Drupal\Core\Config\InstallStorage;
use Drupal\Core\Config\StorageInterface;
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
 *       "edit" = "Drupal\metatag\Form\MetatagDefaultsForm",
 *       "revert" = "Drupal\metatag\Form\MetatagDefaultsRevertForm"
 *     }
 *   },
 *   config_prefix = "metatag_defaults",
 *   admin_permission = "administer meta tags",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label"
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/metatag_defaults/{metatag_defaults}",
 *     "edit-form" = "/admin/structure/metatag_defaults/{metatag_defaults}/edit",
 *     "revert-form" = "/admin/structure/metatag_defaults/{metatag_defaults}/revert",
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

  /**
   * Reverts an entity to its default values.
   */
  public function revert() {
    $config_installer = \Drupal::service('config.installer');
    $default_install_path = drupal_get_path('module', 'metatag') . '/' . InstallStorage::CONFIG_INSTALL_DIRECTORY;
    $storage = new FileStorage($default_install_path, StorageInterface::DEFAULT_COLLECTION);
    $default_config_data = $storage->read('metatag.metatag_defaults.' . $this->id());
    if ($default_config_data) {
      $this->set('tags', $default_config_data['tags']);
      $this->save();
    }
  }

}
