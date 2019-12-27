<?php

namespace Drupal\bh_layout_preview\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Config\ConfigFactoryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Defines a controller to select Image for block.
 */
class PreviewImageController extends ControllerBase {

  /**
   * The config factory service.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * Constructs a ConfigEntityStorage object.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The config factory service.
   */
  public function __construct(ConfigFactoryInterface $config_factory) {
    $this->configFactory = $config_factory;
  }

  /**
   * {@inheritdoc}
   */
  public static function createInstance(ContainerInterface $container) {
    return new static(
      $container->get('config.factory')
    );
  }

  /**
   * Display Image.
   */
  public function dispalyImage($plugin_id) {
    $config = $this->configFactory->getEditable('bh_layout_preview.layout_builder_settings');
    $image_path = $config->get($plugin_id);

    return [
      '#theme' => 'bh_image_preview',
      '#image_path' => $image_path,
    ];
  }

}
