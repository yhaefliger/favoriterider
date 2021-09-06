<?php
declare(strict_types=1);

namespace PrestaShop\Module\FavoriteRider\Controller\Admin;

use PrestaShop\Module\FavoriteRider\Grid\RiderGridDefinitionFactory;
use PrestaShop\Module\FavoriteRider\Grid\RiderGridFilters;
use PrestaShop\PrestaShop\Core\Grid\GridInterface;
use PrestaShopBundle\Controller\Admin\FrameworkBundleAdminController;
use PrestaShopBundle\Security\Annotation\AdminSecurity;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RidersController extends FrameworkBundleAdminController
{
    const TAB_CLASS_NAME = 'AdminRidersController';

    
    /** 
     * Index action
     *
     * @AdminSecurity("is_granted('read', request.get('_legacy_controller'))")
     *
     * @param RiderGridFilters $filters
     * 
     * @return Response
     */
    public function indexAction(
        Request $request,
        RiderGridFilters $riderGridFilters
    ) {
        /** @var PrestaShop\PrestaShop\Core\Grid\GridFactory */
        $ridersGirdFactory = $this->get('prestashop.module.favoriterider.rider_grid_factory');
        $ridersGrid = $ridersGirdFactory->getGrid($riderGridFilters);

        return $this->render('@Modules/favoriterider/views/templates/admin/riders.html.twig', [
            'layoutTitle' => $this->trans('Manage riders', 'Modules.FavoriteRider.Admin'),
            'ridersGrid' => $this->presentGrid($ridersGrid),
        ]);
    }

    /**
     * Search filters
     * 
     * @AdminSecurity("is_granted('read', request.get('_legacy_controller'))")
     *
     * @param Request $request
     * 
     * @return RedirectResponse
     */
    public function searchAction(Request $request)
    {
        /** @var ResponseBuilder $responseBuilder */
        $responseBuilder = $this->get('prestashop.bundle.grid.response_builder');

        return $responseBuilder->buildSearchResponse(
            $this->get('prestashop.module.favoriterider.rider_grid_definition_factory'),
            $request,
            RiderGridDefinitionFactory::GRID_ID,
            'admin_favoriterider_riders_index'
        );
    }
}