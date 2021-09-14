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

use PrestaShop\PrestaShop\Core\Grid\Action\Row\RowActionCollection;
use PrestaShop\PrestaShop\Core\Grid\Action\Row\Type\LinkRowAction;
use PrestaShop\PrestaShop\Core\Grid\Action\Row\Type\SubmitRowAction;
use PrestaShop\PrestaShop\Core\Grid\Column\ColumnCollection;
use PrestaShop\PrestaShop\Core\Grid\Column\Type\Common\ActionColumn;
use PrestaShop\PrestaShop\Core\Grid\Column\Type\Common\BadgeColumn;
use PrestaShop\PrestaShop\Core\Grid\Column\Type\Common\ImageColumn;
use PrestaShop\PrestaShop\Core\Grid\Column\Type\DataColumn;
use PrestaShop\PrestaShop\Core\Grid\Definition\Factory\AbstractGridDefinitionFactory;
use PrestaShop\PrestaShop\Core\Grid\Filter\Filter;
use PrestaShop\PrestaShop\Core\Grid\Filter\FilterCollection;
use PrestaShopBundle\Form\Admin\Type\IntegerMinMaxFilterType;
use PrestaShopBundle\Form\Admin\Type\SearchAndResetType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
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
        return $this->trans('Riders', [], 'Modules.Favoriterider.Admin');
    }

    protected function getColumns()
    {
        return (new ColumnCollection())
            ->add((new DataColumn('id_rider'))
                ->setName($this->trans('ID', [], 'Admin.Global'))
                ->setOptions([
                    'field' => 'id_rider',
                ])
            )
            ->add((new ImageColumn('image'))
                ->setName($this->trans('Rider Photo', [], 'Modules.Favoriterider.Admin'))
                ->setOptions([
                    'src_field' => 'image',
                ])
            )
            ->add((new BadgeColumn('votes'))
                ->setName($this->trans('Votes', [], 'Modules.Favoriterider.Admin'))
                ->setOptions([
                    'field' => 'votes',
                    'empty_value' => '0',
                ])
            )
            ->add((new DataColumn('name'))
                ->setName($this->trans('Rider Name', [], 'Modules.Favoriterider.Admin'))
                ->setOptions([
                    'field' => 'name',
                ])
            )
            ->add((new DataColumn('discipline'))
                ->setName($this->trans('Rider Discipline', [], 'Modules.Favoriterider.Admin'))
                ->setOptions([
                    'field' => 'discipline',
                ])
            )
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
                (new Filter('id_rider', IntegerType::class))
                    ->setAssociatedColumn('id_rider')
                    ->setTypeOptions([
                        'required' => false,
                        'attr' => [
                            'placeholder' => $this->trans('Search ID', [], 'Admin.Actions'),
                        ],
                    ])
            )
            ->add(
                (new Filter('name', TextType::class))
                ->setAssociatedColumn('name')
                ->setTypeOptions([
                    'required' => false,
                    'attr' => [
                        'placeholder' => $this->trans('Search Rider name', [], 'Admin.Actions'),
                    ],
                ])
            )
            ->add(
                (new Filter('discipline', TextType::class))
                ->setAssociatedColumn('discipline')
                ->setTypeOptions([
                    'required' => false,
                    'attr' => [
                        'placeholder' => $this->trans('Search Rider discipline', [], 'Admin.Actions'),
                    ],
                ])
            )
            ->add(
                (new Filter('votes', IntegerMinMaxFilterType::class))
                ->setAssociatedColumn('votes')
                ->setTypeOptions([
                    'required' => false,
                ])
            )
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
    }

    /**
     * Grid row actions
     */
    private function getRowActions()
    {
        return (new RowActionCollection())
            ->add((new LinkRowAction('edit'))
                ->setName($this->trans('Edit', [], 'Admin.Actions'))
                ->setIcon('edit')
                ->setOptions([
                    'route' => 'admin_favoriterider_riders_edit',
                    'route_param_name' => 'riderId',
                    'route_param_field' => 'id_rider',
                ])
            )
            ->add((new SubmitRowAction('delete'))
                ->setName($this->trans('Delete', [], 'Admin.Actions'))
                ->setIcon('delete')
                ->setOptions([
                    'method' => 'DELETE',
                    'route' => 'admin_favoriterider_riders_delete',
                    'route_param_name' => 'riderId',
                    'route_param_field' => 'id_rider',
                    'confirm_message' => $this->trans(
                        'Delete selected item?',
                        [],
                        'Admin.Notifications.Warning'
                    ),
                ])
            )
            ;
    }
}
