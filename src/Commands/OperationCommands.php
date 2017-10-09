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
   * @command fix-node-repository
   * @usage drush fnr
   *  Fix Node Repository.
   * @aliases fnr
   */
  function fix_node_repository() {
    $current_field_storage_definitions = \Drupal::entityManager()
      ->getFieldStorageDefinitions('node');
    \Drupal::service('entity.last_installed_schema.repository')
      ->setLastInstalledFieldStorageDefinitions('node', $current_field_storage_definitions);
  }
}
