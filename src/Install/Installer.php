<?php
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
    if(!file_exists(Rider::RIDER_IMAGE_PATH)){
      mkdir(Rider::RIDER_IMAGE_PATH, 0755);
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
      'displayHome'
    ];

    return $module->registerHook($hooks);
  }

}