<?php

/**
 * @file
 * Contains \Drupal\metatag\MetatagTagListBuilder.
 */

namespace Drupal\metatag;

use Drupal\Core\Config\Entity\ConfigEntityListBuilder;
use Drupal\Core\Entity\EntityInterface;

/**
 * Provides a listing of Metatag tag entities.
 */
class MetatagTagListBuilder extends ConfigEntityListBuilder {
  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['label'] = $this->t('Metatag tag');
    $header['id'] = $this->t('Machine name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    $row['label'] = $this->getLabel($entity);
    $row['id'] = $entity->id();
    // You probably want a few more properties here...
    return $row + parent::buildRow($entity);
  }

}
