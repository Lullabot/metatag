<?php
/**
 * @file
 * Contains \Drupal\metatag\Plugin\metatag\Tag\ImageSrc.
 */

namespace Drupal\metatag\Plugin\metatag\Tag;

use Drupal\Core\Annotation\Translation;
use Drupal\metatag\Plugin\metatag\Tag\MetaNameBase;
use Drupal\metatag\Annotation\MetatagTag;

/**
 * The advanced "Image" meta tag.
 *
 * @MetatagTag(
 *   id = "image_src",
 *   label = @Translation("Image"),
 *   description = @Translation("An image associated with this page, for use as a thumbnail in social networks and other services."),
 *   name = "image_src",
 *   group = "advanced",
 *   weight = 4
 * )
 */
class ImageSrc extends MetaNameBase {
  // Nothing here yet. Just a placeholder class for a plugin.
}
