<?php
/**
 * @file
 * Contains \Drupal\node_operations\Controller\OperationController.
 */

namespace Drupal\node_operations\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\node\Entity\Node;

/**
 * Class OperationController.
 *
 * @package Drupal\node_operations\Controller
 */
class OperationController extends ControllerBase {
  /**
   * Unset the node promoted value.
   * So you can bulk remove the "promoted" flag to all node of some content type
   *
   * @return string
   *   Return a silly string.
   */
  public function promoted($node_type) {

    $query = \Drupal::entityQuery('node')->condition('type', $node_type);

    $nids = $query->execute();

    foreach ($nids as $nid) {
      $node = Node::load($nid);
      $node->promote->setValue(0);
      $node->save();
    }

    return [
      '#type' => 'markup',
      '#markup' => $this->t('Implement method: promoted')
    ];
  }

  /**
   * Slug.
   * After installing https://www.drupal.org/project/pathauto
   * only the entitis with flag "Generate automatic URL alias" set to true
   * This operation change those flag and rebuild the path
   *
   * @return string
   *   Return a silly string.
   */
  public function slug($lang) {

    $query = \Drupal::entityQuery('node')->condition('nid', 0,'>');

    $nids = $query->execute();

    foreach ($nids as $nid) {
      $entity = \Drupal::entityTypeManager()->getStorage('node')->load($nid);
      $entity->path->pathauto = TRUE;
      \Drupal::service('pathauto.manager')->updateAlias($entity, 'update', ['language' => $lang]);
      $entity->save();
    }

    return [
      '#type' => 'markup',
      '#markup' => $this->t('Implement method: slug')
    ];
  }

  /**
   * Type.
   * Change the type of a node.
   * Pay attention: this can produce garbage in your db if content type do not support additional field related to the changing node
   *
   * @return string
   *   Return a silly string.
   */
  public function type($nid,$node_type) {
    $node = Node::load($nid);
    $node->type->setValue($node_type);
    $node->save();

    return [
      '#type' => 'markup',
      '#markup' => $this->t('Implement method: type')
    ];
  }

  /**
   * Lang.
   * Set a consistent language of those node where language is set to UND
   *
   * @return string
   *   Return a silly string.
   */
  public function lang($lang) {

    $query = \Drupal::entityQuery('node')->condition('langcode','und');

    $nids = $query->execute();

    foreach ($nids as $nid) {
      $node = Node::load($nid);
      $node->langcode->setValue($lang);
      $node->save();
    }

    return [
      '#type' => 'markup',
      '#markup' => $this->t('Implement method: lang')
    ];
  }
}
