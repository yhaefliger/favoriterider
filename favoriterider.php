<?php
declare(strict_types=1);

use PrestaShop\Module\FavoriteRider\Controller\Admin\RidersController;
use PrestaShop\Module\FavoriteRider\Repository\RiderRepository;
use PrestaShop\Module\FavoriteRider\Install\Installer;
use PrestaShop\PrestaShop\Core\Module\WidgetInterface;

if (!defined('_PS_VERSION_')) {
  exit;
}

if (file_exists(__DIR__.'/vendor/autoload.php')) {
  require_once __DIR__.'/vendor/autoload.php';
}

class FavoriteRider extends Module implements WidgetInterface
{


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
        'route_name' => 'admin_favoriterider_riders_index',
        'class_name' => RidersController::TAB_CLASS_NAME,
        'visible' => true,
        'name' => $tabNames,
        'parent_class_name' => 'IMPROVE',
        'icon' => 'snowboarding'
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
    
    return parent::install() && $installer->install($this);
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
   * Redirect to riders manage page on module config show
   */
  public function getContent()
  {
    Tools::redirectAdmin(
      $this->context->link->getAdminLink('AdminRidersController')
    );
  }

  /**
   * Homepage hook display top 3 riders
   *
   * @return string
   */
  public function hookDisplayHome()
  {
    $this->context->controller->registerStylesheet('prestashopm.module.favoriterider.front.home', $this->_path.'public/build/front/home.css');
    
    /** @var RiderRepository $repository */
    $repository = $this->get('prestashop.module.favoriterider.repository.rider_repository');
    $riders = $repository->getTopRiders(3);

    $this->smarty->assign(['riders' => $riders]);

    return $this->fetch('module:'.$this->name.'/views/templates/front/home.tpl');
  }

  /**
   * TODO: widget to display on any template the list of all riders
   * 
   * <!-- smarty generic call -->
   * {widget name='favoriterider'}
   * 
   * @param string $hookName
   * @param array $configuration
   * 
   * @return string templating result
   */
  public function renderWidget($hookName, array $configuration) 
  {
    $this->smarty->assign($this->getWidgetVariables($hookName, $configuration));

    return $this->fetch('module:'.$this->name.'/views/templates/widget/riders.tpl');
  }
  /**
   * Widget variables
   *
   * @param string $hookName
   * @param array $configuration
   * 
   * @return array
   */
  public function getWidgetVariables($hookName , array $configuration)
  {
      return [
      ];
  }

  /**
   * Return installer instance
   *
   * @return Installer
   */
  private function getInstaller()
  {
    return new Installer();
  }
}