<?php
declare(strict_types=1);

namespace PrestaShop\Module\FavoriteRider\Grid;

use PrestaShop\PrestaShop\Core\Grid\Column\ColumnCollection;
use PrestaShop\PrestaShop\Core\Grid\Column\Type\DataColumn;
use PrestaShop\PrestaShop\Core\Grid\Definition\Factory\AbstractGridDefinitionFactory;

final class RiderGridDefinitionFactory extends AbstractGridDefinitionFactory
{
    protected function getId()
    {
        return 'rider_entity';
    }

    protected function getName()
    {
        return $this->trans('Riders', [], 'Modules.FavoriteRider.Admin');
    }

    protected function getColumns()
    {
        return (new ColumnCollection())
            ->add((new DataColumn('id_rider'))
                ->setName($this->trans('ID', [], 'Admin.Global'))
                ->setOptions([
                    'field' => 'id_rider'
                ])
            )
            //TODO: add other columns
        ;
    }
}