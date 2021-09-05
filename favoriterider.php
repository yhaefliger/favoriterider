<?php
declare(strict_types=1);

use PrestaShop\Module\FavoriteRider\Controller\Admin\RidersController;
use PrestaShop\Module\FavoriteRider\Utils\Installer;

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
    $this->ps_versions_compliancy = [
      'min' => '1.7.7.0',
      'max' => _PS_VERSION_,
    ];

    parent::__construct();

    $this->displayName = $this->l('Favorite Rider');
    $this->description = $this->l('Let visitors vote for their favorite rider!');
    
    $tabNames = [];
    foreach (Language::getLanguages(true) as $lang) {
      $tabNames[$lang['locale']] = $this->trans('Manage Riders', [], 'Modules.FavoriteRider.Admin', $lang['locale']);
    }
    $this->tabs = [
      [
        'route_name' => 'favoriterider_riders_index',
        'class_name' => RidersController::TAB_CLASS_NAME,
        'visible' => true,
        'name' => $tabNames,
        'parent_class_name' => 'IMPROVE'
      ]
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