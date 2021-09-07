<?php
namespace PrestaShop\Module\FavoriteRider\Grid;

use PrestaShop\PrestaShop\Core\Grid\Data\Factory\GridDataFactoryInterface;
use PrestaShop\PrestaShop\Core\Grid\Data\GridData;
use PrestaShop\PrestaShop\Core\Grid\Record\RecordCollection;
use PrestaShop\PrestaShop\Core\Grid\Search\SearchCriteriaInterface;
use PrestaShop\PrestaShop\Core\Image\ImageProviderInterface;

/**
 * Gets data for manufacturer grid
 */
final class RiderGridDataFactory implements GridDataFactoryInterface
{
    /**
     * @var GridDataFactoryInterface
     */
    private $riderDataFactory;

    /**
     * @var ImageProviderInterface
     */
    private $imageProvider;

    /**
     * @param GridDataFactoryInterface $riderDataFactory
     * @param ImageProviderInterface $imageProvider
     */
    public function __construct(
        GridDataFactoryInterface $riderDataFactory,
        ImageProviderInterface $imageProvider
    ) {
        $this->riderDataFactory = $riderDataFactory;
        $this->imageProvider = $imageProvider;
    }

    /**
     * {@inheritdoc}
     */
    public function getData(SearchCriteriaInterface $searchCriteria)
    {
        $ridersData = $this->riderDataFactory->getData($searchCriteria);

        $modifiedRecords = $this->applyModification(
            $ridersData->getRecords()->all()
        );

        return new GridData(
            new RecordCollection($modifiedRecords),
            $ridersData->getRecordsTotal(),
            $ridersData->getQuery()
        );
    }

    /**
     * @param array $riders
     *
     * @return array
     */
    private function applyModification(array $riders)
    {
        foreach ($riders as $i => $rider) {
            $riders[$i]['image'] = $this->imageProvider->getPath($rider['id_rider']);
        }

        return $riders;
    }
}
