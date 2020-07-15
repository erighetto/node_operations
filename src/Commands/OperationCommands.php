<?php

namespace Drupal\node_operations\Commands;

use Drush\Commands\DrushCommands;

/**
 * Class OperationCommands
 *
 * @package Drupal\node_operations\Commands
 */
class OperationCommands extends DrushCommands {

  /**
   * Fix Node Repository.
   *
   * @command nodeops:fix-node-repository
   * @usage drush nos-fnr
   *  Fix Node Repository.
   * @aliases nos-fnr nodeops:fnr
   */
  public function fix_node_repository() {
    $current_field_storage_definitions = \Drupal::entityManager()
      ->getFieldStorageDefinitions('node');
    \Drupal::service('entity.last_installed_schema.repository')
      ->setLastInstalledFieldStorageDefinitions('node', $current_field_storage_definitions);
  }

  /**
   * Unset the node promoted value.
   * So you can bulk remove the "promoted" flag to all node of some content type
   *
   * @command nodeops:promoted
   * @param $node_type Content type to look for.
   * @usage drush nos-pro article
   *  Unset the node promoted value.
   * @aliases nos-pro
   */
  public function promoted($node_type) {

    $query = \Drupal::entityQuery('node')->condition('type', $node_type);
    $nids = $query->execute();
    $node_storage = \Drupal::entityTypeManager()->getStorage('node');

    foreach ($nids as $nid) {
      /** @var \Drupal\node\Entity\Node $entity */
      $entity = $node_storage->load($nid);
      $entity->promote->setValue(0);
      $entity->save();
    }

  }

  /**
   * Slug.
   * After installing https://www.drupal.org/project/pathauto
   * only the entities with flag "Generate automatic URL alias" set to true are affected on bulk operation.
   * This operation change those flag and rebuild the path
   *
   * @command nodeops:path
   * @param $lang Language code to set.
   * @usage drush nos-pat it
   *  change pathauto flag and rebuild the path.
   * @aliases nos-pat
   */
  public function slug($lang) {

    $query = \Drupal::entityQuery('node')->condition('nid', 0,'>');
    $nids = $query->execute();
    $node_storage = \Drupal::entityTypeManager()->getStorage('node');

    foreach ($nids as $nid) {
      /** @var \Drupal\node\Entity\Node $entity */
      $entity = $node_storage->load($nid);
      $entity->path->pathauto = TRUE;

      \Drupal::service('pathauto.generator')->updateEntityAlias($entity, 'update', ['language' => $lang]);
      $entity->save();
    }

  }

  /**
   * Type.
   * Change the type of a node.
   * Pay attention: this can produce garbage in your db if content type do not support additional field related to the changing node
   *
   * @command nodeops:type
   * @param $nid Node id.
   * @param $node_type Node type to set.
   * @usage drush nos-typ 9 article
   *  Change the type of a node.
   * @aliases nos-typ
   */
  public function type($nid,$node_type) {
    $node_storage = \Drupal::entityTypeManager()->getStorage('node');
    /** @var \Drupal\node\Entity\Node $entity */
    $entity = $node_storage->load($nid);
    $entity->type->setValue($node_type);
    $entity->save();

  }

  /**
   * Lang.
   * Set a consistent language of those node where language is set to UND
   *
   * @command nodeops:lang
   * @param $lang Language code to set.
   * @usage drush nos-lan it
   *  Unset the node promoted value.
   * @aliases nos-lan
   */
  public function lang($lang) {

    $query = \Drupal::entityQuery('node')->condition('langcode','und');
    $nids = $query->execute();
    $node_storage = \Drupal::entityTypeManager()->getStorage('node');

    foreach ($nids as $nid) {
      /** @var \Drupal\node\Entity\Node $entity */
      $entity = $node_storage->load($nid);
      $entity->langcode->setValue($lang);
      $entity->save();
    }

  }

}
