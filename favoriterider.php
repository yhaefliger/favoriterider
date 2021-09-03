<?php
declare(strict_types=1);

use PrestaShop\Module\FavoriteRider\Tools\Installer;

if (!defined('_PS_VERSION_')) {
  exit;
}

if (file_exists(__DIR__.'/vendor/autoload.php')) {
  require_once __DIR__.'/vendor/autoload.php';
}

class FavoriteRider extends Module 
{

  /**
   * Riders uploaded images path
   */
  private const RIDER_IMAGE_PATH = '/img/rider';

  public function __construct()
  {
    $this->name = 'favoriterider';
    $this->version = '1.0.0';
    $this->author = 'Yann Haefliger';
    $this->need_instance = 0;

    parent::__construct();

    $this->displayName = $this->getTranslator()->trans(
      'Favorite Rider',
      [],
      'Modules.Favoriterider.Admin'
    );

    $this->description = $this->getTranslator()->trans(
      'Let visitors vote for their favorite rider!',
      [],
      'Modules.Favoriterider.Admin'
    );

    $this->ps_versions_compliancy = [
      'min' => '1.7.7.0',
      'max' => _PS_VERSION_,
    ];
  }

  /**
   * Make module compatible with new translation system
   *
   * @return boolean
   */
  public function isUsingNewTranslationSystem()
  {
    return true;
  }

  /**
   * Module installation (hooks and sql)
   *
   * @return bool
   */
  public function install()
  {
    $installer = $this->getInstaller();
    
    return $installer->install() && parent::install();
    
  }

  /**
   * Module uninstallation phase
   *
   * @return bool
   */
  public function uninstall()
  {
    $installer = $this->getInstaller();

    return $installer->uninstall() && parent::uninstall();
  }

  /**
   * Return installer instance
   * TODO: try to get it from service container with Doctrine injected 
   *
   * @return Installer
   */
  private function getInstaller()
  {
    return new Installer();
  }
}