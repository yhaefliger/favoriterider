<?php
declare(strict_types=1);

namespace PrestaShop\Module\FavoriteRider\Grid;

use PrestaShop\PrestaShop\Core\Grid\Action\Row\RowActionCollection;
use PrestaShop\PrestaShop\Core\Grid\Action\Row\Type\LinkRowAction;
use PrestaShop\PrestaShop\Core\Grid\Column\ColumnCollection;
use PrestaShop\PrestaShop\Core\Grid\Column\Type\Common\ActionColumn;
use PrestaShop\PrestaShop\Core\Grid\Column\Type\DataColumn;
use PrestaShop\PrestaShop\Core\Grid\Definition\Factory\AbstractGridDefinitionFactory;
use PrestaShop\PrestaShop\Core\Grid\Filter\Filter;
use PrestaShop\PrestaShop\Core\Grid\Filter\FilterCollection;
use PrestaShopBundle\Form\Admin\Type\SearchAndResetType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

final class RiderGridDefinitionFactory extends AbstractGridDefinitionFactory
{
    const GRID_ID = 'rider_entity';

    protected function getId()
    {
        return self::GRID_ID;
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
            ->add((new DataColumn('name'))
                ->setName($this->trans('Rider Name', [], 'Modules.FavoriteRier.Admin'))
                ->setOptions([
                    'field' => 'name'
                ])
            )
            //TODO: add other columns
            ->add((new ActionColumn('actions'))
                ->setName($this->trans('Actions', [], 'Admin.Actions'))
                ->setOptions([
                    'actions' => $this->getRowActions(),
                ])
            )
        ;
    }

    protected function getFilters()
    {
        return (new FilterCollection())
            ->add(
                (new Filter('id_rider', TextType::class))
                    ->setAssociatedColumn('id_rider')
                    ->setTypeOptions([
                        'required' => false,
                        'attr' => [
                            'placeholder' => $this->trans('Search ID', [], 'Admin.Actions')
                        ]
                    ])
            )
            //TODO: add other columns
            ->add(
                (new Filter('actions', SearchAndResetType::class))
                    ->setAssociatedColumn('actions')
                    ->setTypeOptions([
                        'reset_route' => 'admin_common_reset_search_by_filter_id',
                        'reset_route_params' => [
                            'filterId' => self::GRID_ID,
                        ],
                        'redirect_route' => 'admin_favoriterider_riders_index',
                    ])
            );
        ;
    }

    /**
     * Grid row actions
     */
    private function getRowActions()
    {
        return (new RowActionCollection())
            ->add((new LinkRowAction('edit'))
                ->setName($this->trans('Edit', [], 'Admin.Actions'))
                ->setOptions([
                    'route' => 'admin_favoriterider_riders_edit',
                    'route_param_name' => 'riderId',
                    'route_param_field' => 'id_rider',
                ])
                ->setIcon('edit')
            )
            ;
    }
}