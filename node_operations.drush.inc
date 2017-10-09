<?php
/**
 * @file Contains the code to generate the custom drush commands.
 */

/**
 * Implements hook_drush_command().
 */
function node_operations_drush_command() {
  $items = [];
  $items['fix-node-repository'] = [
    'description' => 'Fix Node Repository',
  ];
  return $items;
}

/**
 * Call back function.
 *
 * The call back function name in the  following format
 *   drush_{module_name}_{item_id_for_command}()
 */
function drush_node_operations_fix_node_repository() {
  $current_field_storage_definitions = \Drupal::entityManager()
    ->getFieldStorageDefinitions('node');
  \Drupal::service('entity.last_installed_schema.repository')
    ->setLastInstalledFieldStorageDefinitions('node', $current_field_storage_definitions);
}
