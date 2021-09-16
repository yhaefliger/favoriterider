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
use PrestaShop\Module\FavoriteRider\Install\Installer;
use PrestaShop\Module\FavoriteRider\Presenter\RiderPresenter;
use PrestaShop\Module\FavoriteRider\Repository\RiderRepository;
use PrestaShop\PrestaShop\Adapter\CMS\CMSDataProvider;

if (!defined('_PS_VERSION_')) {
    exit;
}

if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
}

class FavoriteRider extends Module
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

        $this->displayName = $this->trans('Favorite Rider', [], 'Modules.Favoriterider.Admin');
        $this->description = $this->trans('Let visitors vote for their favorite rider!', [], 'Modules.Favoriterider.Admin');

        $tabNames = [];
        foreach (Language::getLanguages(true) as $lang) {
            $tabNames[$lang['locale']] = $this->trans('Manage Riders', [], 'Modules.Favoriterider.Admin', $lang['locale']);
        }
        $this->tabs = [
            [
                'route_name' => 'admin_favoriterider_riders_index',
                'class_name' => RidersController::TAB_CLASS_NAME,
                'visible' => true,
                'name' => $tabNames,
                'parent_class_name' => 'IMPROVE',
                'icon' => 'snowboarding',
            ],
        ];

        $this->assetsPath = $this->_path . 'public/build/';
    }

    /**
     * Make module compatible with new translation system
     *
     * @return bool
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
     * Display and process admin configuration page
     *
     * @return string
     */
    public function getContent()
    {
        $output = '';

        $cmsDataProvider = new CMSDataProvider();
        $pages = $cmsDataProvider->getCMSChoices($this->context->language->id);

        if (Tools::isSubmit('submit' . $this->name)) {
            $cmsPageId = Tools::getValue('FAVORITERIDER_CMS_PAGE_ID');
            if ($cmsPageId && !empty($cmsPageId)) {
                if (!in_array($cmsPageId, $pages)) {
                    $output = $this->displayError($this->trans('Invalid cms page', [], 'Modules.Favoriterider.Admin'));
                } else {
                    Configuration::updateValue('FAVORITERIDER_CMS_PAGE_ID', $cmsPageId);
                    $output = $this->displayConfirmation($this->trans('The settings have been updated.', [], 'Admin.Notifications.Success'));
                }
            }
        }

        return $output . $this->displayForm($pages);
    }

    /**
     * Admin module configuration form
     *
     * @param array $pages Choices Pages
     *
     * @return string
     */
    public function displayForm(array $pages): string
    {
        $pagesOptions = [];
        foreach ($pages as $name => $id) {
            $pagesOptions[] = [
                'id' => $id,
                'name' => $name,
            ];
        }

        $form = [
            'form' => [
                'legend' => [
                    'title' => $this->trans('Settings', [], 'Admin.Global'),
                ],
                'input' => [
                    [
                        'type' => 'select',
                        'label' => $this->trans('CMS Page', [], 'Modules.Favoriterider.Admin'),
                        'desc' => $this->trans('Page on which the widget will be rendered', [], 'Modules.Favoriterider.Admin'),
                        'name' => 'FAVORITERIDER_CMS_PAGE_ID',
                        'required' => false,
                        'options' => [
                            'query' => $pagesOptions,
                            'id' => 'id',
                            'name' => 'name',
                        ],
                    ],
                ],
                'submit' => [
                    'title' => $this->trans('Save', [], 'Admin.Global'),
                ],
            ],
            ];

        $helper = new HelperForm();
        $helper->table = $this->table;
        $helper->name_controller = $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex . '&' . http_build_query(['configure' => $this->name]);
        $helper->submit_action = 'submit' . $this->name;
        $helper->default_form_language = (int) Configuration::get('PS_LANG_DEFAULT');
        $helper->show_toolbar = false;

        $helper->fields_value['FAVORITERIDER_CMS_PAGE_ID'] = Tools::getValue('FAVORITERIDER_CMS_PAGE_ID', Configuration::get('FAVORITERIDER_CMS_PAGE_ID'));

        return $helper->generateForm([$form]);
    }

    /**
     * Homepage hook display top 3 riders
     *
     * @return string
     */
    public function hookDisplayHome(): string
    {
        $this->context->controller->registerStylesheet('favoriterider-home-styles', $this->assetsPath . 'front/home.css');

        $ridersRepository = $this->getRepository();
        $riders = $ridersRepository->getTopRiders(3);

        $presenter = new RiderPresenter();
        $presentedRiders = array_map(function ($rider) use ($presenter) {
            return $presenter->present($rider, 'lg');
        }, $riders);

        //link to vote page
        $cmsPageId = (int) Configuration::get('FAVORITERIDER_CMS_PAGE_ID');
        if ($cmsPageId && $cmsPageId > 0) {
            $link = $this->context->link->getCMSLink($cmsPageId);
        } else {
            $link = false;
        }

        $this->smarty->assign([
            'riders' => $presentedRiders,
            'link' => $link,
        ]);

        return $this->fetch('module:' . $this->name . '/views/templates/front/home.tpl');
    }

    /**
     * Header styles & scripts
     * 
     * @param array $params
     * 
     * @return void
     */
    public function hookDisplayHeader(array $params): void
    {
        if ($this->displayWidget()) {
            //riders wiget assets
            $this->context->controller->registerStylesheet('favoriterider-widget-riders', $this->assetsPath . 'front/widget/riders.css');
            $this->context->controller->registerJavascript('favoriterider-widget-riders', $this->assetsPath . 'front/widget/riders.js', ['position' => 'bottom', 'priority' => 2000]);
        }
    }

    /**
     * Page content hook
     *
     * @param array $params
     *
     * @return string
     */
    public function hookDisplayContentWrapperBottom(array $params): string
    {
        if ($this->displayWidget()) {
            $this->smarty->assign($this->getRidersWidgetVariables());

            return $this->fetch('module:' . $this->name . '/views/templates/front/widget/riders.tpl');
        }

        return '';
    }

    /**
     * Get riders widget data
     *
     * @return array data
     */
    public function getRidersWidgetVariables(): array
    {
        //retrieve riders
        $ridersRepository = $this->getRepository();
        $riders = $ridersRepository->getAll();

        $presenter = new RiderPresenter();

        $presentedRiders = array_map(function ($rider) use ($presenter) {
            return $presenter->present($rider);
        }, $riders);

        //assign riders position by number of votes
        $sortedRiders = $presentedRiders;
        uasort($sortedRiders, function ($a, $b) {
            return ($a['votes'] < $b['votes']) ? 1 : -1;
        });
        $position = 1;
        foreach (array_keys($sortedRiders) as $key) {
            $presentedRiders[$key]['position'] = $position;
            ++$position;
        }

        //vote controller link
        $voteUrl = $this->context->link->getModuleLink(
        'favoriterider',
        'vote'
        );

        //check already voted rider
        $current = 0;
        $voted = $votedId = false;
        if ($this->context->cookie->favorite_rider) {
            foreach ($presentedRiders as $key => $rider) {
                if ($rider['id'] == $this->context->cookie->favorite_rider) {
                    $current = $key;
                    $voted = $rider;
                    $votedId = $rider['id'];
                }
            }
        }

        return [
            'riders' => $presentedRiders,
            'current' => $current,
            'voted' => $voted,
            'voted_id' => $votedId,
            'vote_url' => $voteUrl,
        ];
    }

    /**
     * Test if the widget should be displayed
     *
     * @return bool
     */
    private function displayWidget(): bool
    {
        //check current controller CMS and page id from module configuration
        if ($this->context->controller instanceof CmsController) {
            $cmsPageId = (int) Configuration::get('FAVORITERIDER_CMS_PAGE_ID');
            if ($cmsPageId && $this->context->controller->cms->id === $cmsPageId) {
                return true;
            }
        }

        return false;
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
