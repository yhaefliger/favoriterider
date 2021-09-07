<?php
declare(strict_types=1);

namespace PrestaShop\Module\FavoriteRider\Repository;

use Doctrine\ORM\EntityRepository;

class RiderRepository extends EntityRepository
{
    /**
     * Return riders with the most votes
     *
     * @param integer $nbRiders
     * @return array 
     */
    public function getTopRiders($nbRiders = 3)
    {
        return $this->findBy([], ['votes' => 'DESC'], (int) $nbRiders);
    }
}