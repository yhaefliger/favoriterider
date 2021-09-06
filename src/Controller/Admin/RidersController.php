<?php
declare(strict_types=1);

namespace PrestaShop\Module\FavoriteRider\Controller\Admin;

use Doctrine\ORM\EntityManagerInterface;
use PrestaShop\Module\FavoriteRider\Form\RiderType;
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

        return $this->render('@Modules/favoriterider/views/templates/admin/riders/index.html.twig', [
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
    
    /**
     * Create/Edit form
     * 
     * @AdminSecurity("is_granted(['create'], request.get('_legacy_controller'))")
     *
     *
     * @param Request $request
     * 
     * @return Response
     */
    public function createAction(Request $request)
    {
        $riderFormBuilder = $this->get('prestashop.module.favoriterider.form.identifiable_object.builder.rider_form_builder');
        $riderForm = $riderFormBuilder->getForm();
        $riderForm->handleRequest($request);

        $riderFormHandler =  $this->get('prestashop.module.favoriterider.form.identifiable_object.handler.rider_form_handler');
        $result = $riderFormHandler->handle($riderForm);

        if (null !== $result->getIdentifiableObjectId()) {
            $this->addFlash(
                'success',
                $this->trans('Successful creation.', 'Admin.Notifications.Success')
            );

            return $this->redirectToRoute('admin_favoriterider_riders_index');
        }

        return $this->render('@Modules/favoriterider/views/templates/admin/riders/create.html.twig', [
            'layoutTitle' => $this->trans('Manage riders', 'Modules.FavoriteRider.Admin'),
            'riderForm' => $riderForm->createView(),
        ]);
    }

    /**
     * Create/Edit form
     * 
     * @AdminSecurity("is_granted(['update'], request.get('_legacy_controller'))")
     *
     *
     * @param Request $request
     * @param int $riderId
     *
     * @return Response
     */
    public function editAction(Request $request, $riderId)
    {
        return $this->render('@Modules/favoriterider/views/templates/admin/riders/edit.html.twig', [
            'layoutTitle' => $this->trans('Manage riders', 'Modules.FavoriteRider.Admin'),
        ]);
    }
}