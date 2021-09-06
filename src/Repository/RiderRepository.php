<?php
declare(strict_types=1);

namespace PrestaShop\Module\FavoriteRider\Repository;

use Doctrine\ORM\EntityRepository;

class RiderRepository extends EntityRepository
{
    //TODO: mehtod to retrieve riders ordered by votes desc and limited (top 3, ...)
}