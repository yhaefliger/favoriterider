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

namespace PrestaShop\Module\FavoriteRider\Install;

use Db;
use Module;
use PrestaShop\Module\FavoriteRider\Entity\Rider;

/**
 * Class Installer
 * @package PrestaShop\Module\FavoriteRider\Utils
 */
class Installer 
{

  /**
   * Module install process
   *
   * @return bool
   */
  public function install(Module $module): bool
  {
    
    // create riders images repository if not present
    if(!file_exists(Rider::IMAGE_PATH)){
      mkdir(Rider::IMAGE_PATH, 0755);
    }

    $queries = [
      'CREATE TABLE IF NOT EXISTS `' . pSQL(_DB_PREFIX_) . 'rider` (
        `id_rider` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
        `name` varchar(64) NOT NULL,
        `discipline` varchar(255) NOT NULL,
        `active` tinyint(1) unsigned NOT NULL DEFAULT 1,
        `position` int(10) unsigned NOT NULL DEFAULT 0,
        `votes` int(10) unsigned NOT NULL DEFAULT 0,
        PRIMARY KEY (`id_rider`)
      ) ENGINE=' . pSQL(_MYSQL_ENGINE_) . ' COLLATE=utf8_unicode_ci;'
    ];
    
    return $this->executeQueries($queries) && $this->registerHooks($module);
  }

  /**
   * Module uninstall process
   *
   * @return bool
   */
  public function uninstall(): bool
  {
    $queries = [
      'DROP TABLE IF EXISTS `' . pSQL(_DB_PREFIX_) . 'rider`'
    ];

    return $this->executeQueries($queries);
  }

  /**
   * A helper that executes multiple database queries.
   *
   * @param array $queries
   *
   * @return bool
   */
  private function executeQueries(array $queries): bool
  {
    foreach ($queries as $query) {
      if (!Db::getInstance()->execute($query)) {
        return false;
      }
    }

    return true;
  }

  /**
   * Register module hooks
   *
   * @param Module $module
   * @return boolean
   */
  private function registerHooks(Module $module): bool
  {
    $hooks = [
      'displayHome',
      'displayHeader',
      'displayRidersContent',
    ];

    return $module->registerHook($hooks);
  }

}