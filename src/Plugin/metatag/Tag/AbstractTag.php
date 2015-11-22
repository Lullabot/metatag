<?php
/**
 * @file
 * Contains \Drupal\metatag\Plugin\metatag\Tag\AbstractTag.
 */

namespace Drupal\metatag\Plugin\metatag\Tag;

use Drupal\Core\Annotation\Translation;
use Drupal\metatag\Plugin\metatag\Tag\MetaNameBase;
use Drupal\metatag\Annotation\MetatagTag;

/**
 * The basic "Abstract" meta tag.
 *
 * @MetatagTag(
 *   id = "abstract",
 *   label = @Translation("Abstract"),
 *   description = @Translation("A brief and concise summary of the page's content, preferably 150 characters or less. The description meta tag may be used by search engines to display a snippet about the page in search results."),
 *   name = "abstract",
 *   group = "basic",
 *   weight = 3
 * )
 */
class AbstractTag extends MetaNameBase {

  /**
   * {@inheritdoc}
   */
  public function form() {
    $form = array(
      '#type' => 'textarea',
      '#title' => $this->label(),
      '#default_value' => $this->value(),
      '#required' => FALSE,
      '#description' => $this->description(),
      '#element_validate' => array(array(get_class($this), 'validateTag')),
    );

    return $form;
  }

}