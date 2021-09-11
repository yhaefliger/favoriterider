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
const path = require('path');
const BrowserSyncPlugin = require('browser-sync-webpack-plugin');
const Encore = require('@symfony/webpack-encore');

// Manually configure the runtime environment if not already configured yet by the "encore" command.
// It's useful when you use tools that rely on webpack.config.js file.
if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

//Admin configurations / tooling
Encore
    .enablePostCssLoader()
    .setOutputPath('public/build/admin')
    .setPublicPath('/modules/favoriterider/public/build/admin')
    .setManifestKeyPrefix('build/')
    //admin
    .addEntry('riders/index', './assets/admin/riders/index.js')
    .enableBuildNotifications()
    .enableSassLoader()
    .disableSingleRuntimeChunk()
    .cleanupOutputBeforeBuild()
    .enableSourceMaps(!Encore.isProduction())
;

const adminConfig = Encore.getWebpackConfig();
adminConfig.resolve.alias = {
    '@app': path.resolve(__dirname, '../../admin-dev/themes/new-theme/js/app'),
    '@js': path.resolve(__dirname, '../../admin-dev/themes/new-theme/js'),
    '@pages': path.resolve(__dirname, '../../admin-dev/themes/new-theme/js/pages'),
    '@components': path.resolve(__dirname, '../../admin-dev/themes/new-theme/js/components'),
};
adminConfig.name = 'adminConfig';

Encore.reset();

//Front configurations / tooling
Encore
    .enablePostCssLoader()
    .setOutputPath('public/build/front')
    .setPublicPath('/modules/favoriterider/public/build/front')
    .setManifestKeyPrefix('build/')
    .addEntry('widget/riders', './assets/front/widget/riders.js')
    .addStyleEntry('home', './assets/front/hooks/home.scss')
    .enableBuildNotifications()
    .enableSassLoader()
    .disableSingleRuntimeChunk()
    .cleanupOutputBeforeBuild()
    .enableSourceMaps(!Encore.isProduction())
;

const frontConfig = Encore.getWebpackConfig();
frontConfig.plugins.push(
    new BrowserSyncPlugin(
        {
            proxy: 'prestadev.local',
            files: [
                {
                    match: ['public/build/front/**/*.js', 'public/build/front/**/*.css', 'views/templates/front/**/*.tpl'],
                }
            ]
        },{
            reload: false
        }
    )
);
frontConfig.name = 'frontConfig';

module.exports = [adminConfig, frontConfig];