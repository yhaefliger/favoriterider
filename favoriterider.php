<?php
/**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to https://devdocs.prestashop.com/ for more information.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 */

declare(strict_types=1);

use PrestaShop\Module\FavoriteRider\Controller\Admin\RidersController;
use PrestaShop\Module\FavoriteRider\Entity\Rider;
use PrestaShop\Module\FavoriteRider\Repository\RiderRepository;
use PrestaShop\Module\FavoriteRider\Install\Installer;
use PrestaShop\Module\FavoriteRider\Presenter\RiderPresenter;
use PrestaShop\PrestaShop\Core\Module\WidgetInterface;

if (!defined('_PS_VERSION_')) {
  exit;
}

if (file_exists(__DIR__.'/vendor/autoload.php')) {
  require_once __DIR__.'/vendor/autoload.php';
}

class FavoriteRider extends Module implements WidgetInterface
{

  /**
   * Public assets path
   *
   * @var string
   */
  protected $assetsPath;

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

    $this->assetsPath =  $this->_path.'public/build/';
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
    
    $this->context->controller->registerStylesheet('favoriterider-home-styles', $this->assetsPath.'front/home.css');

    $ridersRepository = $this->getRepository();
    $riders = $ridersRepository->getTopRiders(3);

    $presenter = new RiderPresenter();
    $presentedRiders = array_map(function ($rider) use($presenter) {
      return $presenter->present($rider, 'lg');
    }, $riders);

    $this->smarty->assign(['riders' => $presentedRiders]);

    return $this->fetch('module:'.$this->name.'/views/templates/front/home.tpl');
  }

  /**
   * Header styles & scripts
   */
  public function hookDisplayHeader($params)
  {
    if ($this->context->controller instanceof CmsController) {
      //riders wiget assets
      $this->context->controller->registerStylesheet('favoriterider-widget-riders', $this->assetsPath.'front/widget/riders.css');
      $this->context->controller->registerJavascript('favoriterider-widget-riders', $this->assetsPath.'front/widget/riders.js', ['position' => 'bottom', 'priority' => 2000]);
    }
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

    return $this->fetch('module:'.$this->name.'/views/templates/front/widget/riders.tpl');
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
    //retrieve riders
    $ridersRepository = $this->getRepository();
    $riders = $ridersRepository->getAll();

    $presenter = new RiderPresenter();
    
    return array_map(function ($rider) use($presenter) {
      return $presenter->present($rider);
    }, $riders);
  }

  /**
   * Retrieve riders repository
   *
   * @return RiderRepository
   */
  private function getRepository(): RiderRepository
  {
    return $this->get('prestashop.module.favoriterider.repository.rider_repository');
  }

  /**
   * Return installer instance
   *
   * @return Installer
   */
  private function getInstaller(): Installer
  {
    return new Installer();
  }
}