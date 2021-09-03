<?php
if (!defined('_PS_VERSION_')) {
  exit;
}

class FavoriteRider extends Module 
{
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
    return parent::install() && 
    $this->installTables();
    
  }

  /**
   * Module uninstallation phase
   *
   * @return bool
   */
  public function uninstall()
  {
    return parent::uninstall() && 
    $this->uninstallTables();
  }

  /**
   * Create DB table at installation
   *
   * @return bool
   */
  private function installTables()
  {
    $sql = '
      CREATE TABLE IF NOT EXISTS `' . pSQL(_DB_PREFIX_) . 'favoriterider_rider` (
        `id_rider` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
        `name` varchar(64) NOT NULL,
        `active` tinyint(1) unsigned NOT NULL DEFAULT 1,
        `position` int(10) unsigned NOT NULL DEFAULT 0,
        `votes` int(10) unsigned NOT NULL DEFAULT 0,
        PRIMARY KEY (`id_rider`)
      ) ENGINE=' . pSQL(_MYSQL_ENGINE_) . ' COLLATE=utf8_unicode_ci;
    ';

    return Db::getInstance()->execute($sql);
  }

  /**
   * Remove DB table on uninstall
   *
   * @return bool
   */
  private function uninstallTables() 
  {
    $sql = 'DROP TABLE IF EXISTS `' . pSQL(_DB_PREFIX_) . 'favoriterider_rider`';

    return Db::getInstance()->execute($sql);
  }
}