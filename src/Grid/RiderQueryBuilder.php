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
            //TODO: change fitlering behaviour based on fitlerName column (id=, nb votes < and >?)
            $qb->andWhere("$filterName LIKE :$filterName");
            $qb->setParameter($filterName, '%' . $filterValue . '%');
        }

        return $qb;
    }
}