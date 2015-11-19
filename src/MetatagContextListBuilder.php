<?php

/**
 * @file
 * Contains \Drupal\metatag\MetatagContextListBuilder.
 */

namespace Drupal\metatag;

use Drupal\Core\Config\Entity\ConfigEntityListBuilder;
use Drupal\Core\Entity\EntityInterface;

/**
 * Provides a listing of Metatag context entities.
 */
class MetatagContextListBuilder extends ConfigEntityListBuilder {
  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['label'] = $this->t('Context');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    $row['label'] = $this->getLabel($entity);
    // You probably want a few more properties here...
    return $row + parent::buildRow($entity);
  }

}
