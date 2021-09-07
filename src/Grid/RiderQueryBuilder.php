<?php
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
        foreach ($filters as $filterName => $filterValue) {
            if ('id_rider' === $filterName) {
                $qb->andWhere("$filterName = :$filterName");
                $qb->setParameter($filterName, (int) $filterValue);
            } elseif ('votes' === $filterName) {
                if(isset($filterValue['min_field']) && !empty($filterValue['min_field'])){
                    $qb->andWhere("$filterName >= :$filterName");
                    $qb->setParameter($filterName, $filterValue['min_field']);
                }
                if(isset($filterValue['max_field']) && !empty($filterValue['max_field'])){                    
                    $qb->andWhere("$filterName <= :$filterName");
                    $qb->setParameter($filterName, $filterValue['max_field']);
                }
            } else {
                $qb->andWhere("$filterName LIKE :$filterName");
                $qb->setParameter($filterName, '%' . $filterValue . '%');
            }
        }

        return $qb;
    }
}