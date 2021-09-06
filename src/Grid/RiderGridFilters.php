<?php
declare(strict_types=1);

namespace PrestaShop\Module\FavoriteRider\Grid;

use PrestaShop\PrestaShop\Core\Search\Filters;

final class RiderGridFilters extends Filters
{
  /** @var string */
  protected $filterId = RiderGridDefinitionFactory::GRID_ID;  

  /**
   * {@inheritDoc}
   */
  public static function getDefaults()
  {
    return [
      'limit' => 20,
      'offset' => 0,
      'orderBy' => 'id_rider',
      'sortOrder' => 'desc',
      'filters' => [],
    ];
  }
}