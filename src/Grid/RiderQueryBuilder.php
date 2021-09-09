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

namespace PrestaShop\Module\FavoriteRider\Grid;

use Doctrine\DBAL\Query\QueryBuilder;
use PrestaShop\PrestaShop\Core\Grid\Query\AbstractDoctrineQueryBuilder;
use PrestaShop\PrestaShop\Core\Grid\Search\SearchCriteriaInterface;

final class RiderQueryBuilder extends AbstractDoctrineQueryBuilder
{

    /**
     * {@inheritdoc}
     */
    public function getSearchQueryBuilder(SearchCriteriaInterface $searchCriteria)
    {
        $qb = $this->getBaseQuery($searchCriteria->getFilters());

        return $qb->orderBy(
            $searchCriteria->getOrderBy(),
            $searchCriteria->getOrderWay()
            )
            ->setFirstResult($searchCriteria->getOffset())
            ->setMaxResults($searchCriteria->getLimit());
    }

    /**
     * {@inheritdoc}
     */
    public function getCountQueryBuilder(SearchCriteriaInterface $searchCriteria)
    {
        $qb = $this->getBaseQuery($searchCriteria->getFilters());
        $qb->select('COUNT(r.id_rider)');

        return $qb;
    }

    /**
     * Base query is the same for both searching and counting
     *
     * @param array $filters
     *
     * @return QueryBuilder
     */
    private function getBaseQuery(array $filters)
    {
        $qb = $this->connection
            ->createQueryBuilder()
            ->select('*')
            ->from($this->dbPrefix . 'rider', 'r')
        ;
        
        //Apply filters
        foreach ($filters as $filterName => $filterValue) {
            //id_rider exact value match
            if ('id_rider' === $filterName) {
                $qb->andWhere("$filterName = :$filterName");
                $qb->setParameter($filterName, (int) $filterValue);
            //number of votes (min-max filter)
            } elseif ('votes' === $filterName) {
                if(isset($filterValue['min_field']) && !empty($filterValue['min_field'])){
                    $qb->andWhere("$filterName >= :$filterName");
                    $qb->setParameter($filterName, $filterValue['min_field']);
                }
                if(isset($filterValue['max_field']) && !empty($filterValue['max_field'])){                    
                    $qb->andWhere("$filterName <= :$filterName");
                    $qb->setParameter($filterName, $filterValue['max_field']);
                }
            //other filters -> like %%
            } else {
                $qb->andWhere("$filterName LIKE :$filterName");
                $qb->setParameter($filterName, '%' . $filterValue . '%');
            }
        }

        return $qb;
    }
}