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
    if ($entity->id() != 'global') {
      $output .= '<div>
                   <p>Inherits meta tags from: Global</p>
                 </div>';
      $prefix = '<div class="indentation"></div>';
    }
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
