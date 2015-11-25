<?php

/**
 * @file
 * Contains \Drupal\metatag\MetatagDefaultsListBuilder.
 */

namespace Drupal\metatag;

use Drupal\Core\Config\Entity\ConfigEntityListBuilder;
use Drupal\Core\Entity\EntityInterface;

/**
 * Provides a listing of Metatag defaults entities.
 */
class MetatagDefaultsListBuilder extends ConfigEntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function load() {
    $entities = parent::load();
    // Move the Global defaults to the top.
    return array('global' => $entities['global']) + $entities;
  }

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['label'] = $this->t('Type');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    $row['label'] = $this->getLabelAndConfig($entity);
    return $row + parent::buildRow($entity);
  }

  /**
   * Renders the Metatag defaults lable plus its configuration.
   *
   * @param EntityInterface $entity
   *   The Metatag defaults entity.
   * @return
   *   Render array for a table cell.
   */
  public function getLabelAndConfig(EntityInterface $entity) {
    $output = '<div>';
    $prefix = '';
    $inherits = '';
    if ($entity->id() != 'global') {
      $prefix = '<div class="indentation"></div>';
      $inherits .= 'Global';
    }
    if (strpos($entity->id(), '__') !== FALSE) {
      $prefix .= '<div class="indentation"></div>';
      list($entity_label, $bundle_label) = explode(': ', $entity->get('label'));
      $inherits .= ', ' . $entity_label;
    }
    $output .= '<div>
                  <p>Inherits meta tags from: ' . $inherits . '</p>
                </div>';
    $tags = $entity->get('tags');
    if (count($tags)) {
      $output .= '<table>
                    <tbody>';
      foreach ($tags as $tag_id => $tag_value) {
        $output .= '<tr><td>' . $tag_id . ':</td><td>' . $tag_value . '</td></tr>';
      }
      $output .= '</tbody></table>';
    }

    $output .= '</div></div>';

    return array(
      'data' => array(
        '#type' => 'details',
        '#prefix' => $prefix,
        '#title' => $this->getLabel($entity),
        'config' => array(
          '#markup' => $output,
        ),
      ),
    );
  }

}
