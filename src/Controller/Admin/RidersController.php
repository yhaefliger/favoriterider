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

namespace PrestaShop\Module\FavoriteRider\Controller\Admin;

use Doctrine\ORM\EntityManagerInterface;
use PrestaShop\Module\FavoriteRider\Grid\RiderGridDefinitionFactory;
use PrestaShop\Module\FavoriteRider\Grid\RiderGridFilters;
use PrestaShop\Module\FavoriteRider\Repository\RiderRepository;
use PrestaShop\PrestaShop\Core\Form\IdentifiableObject\Builder\FormBuilder;
use PrestaShop\PrestaShop\Core\Form\IdentifiableObject\Handler\FormHandler;
use PrestaShop\PrestaShop\Core\Foundation\Database\EntityNotFoundException;
use PrestaShop\PrestaShop\Core\Grid\GridFactory;
use PrestaShopBundle\Controller\Admin\FrameworkBundleAdminController;
use PrestaShopBundle\Security\Annotation\AdminSecurity;
use PrestaShopBundle\Service\Grid\ResponseBuilder;
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
     * @param Request $request
     * @param RiderGridFilters $riderGridFilters
     *
     * @return Response
     */
    public function indexAction(Request $request, RiderGridFilters $riderGridFilters)
    {
        /** @var GridFactory $ridersGirdFactory */
        $ridersGirdFactory = $this->get('prestashop.module.favoriterider.rider_grid_factory');
        $ridersGrid = $ridersGirdFactory->getGrid($riderGridFilters);

        return $this->render('@Modules/favoriterider/views/templates/admin/riders/index.html.twig', [
            'layoutTitle' => $this->trans('Manage riders', 'Modules.Favoriterider.Admin'),
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
    public function searchAction(Request $request): RedirectResponse
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
     * @param Request $request
     *
     * @return Response
     */
    public function createAction(Request $request): Response
    {
        $riderFormBuilder = $this->getFormBuilder();
        $riderForm = $riderFormBuilder->getForm();
        $riderForm->handleRequest($request);

        $riderFormHandler = $this->getFormHandler();
        $result = $riderFormHandler->handle($riderForm);

        if (null !== $result->getIdentifiableObjectId()) {
            $this->addFlash(
                'success',
                $this->trans('Successful creation.', 'Admin.Notifications.Success')
            );

            return $this->redirectToRoute('admin_favoriterider_riders_index');
        }

        return $this->render('@Modules/favoriterider/views/templates/admin/riders/create.html.twig', [
            'layoutTitle' => $this->trans('Manage riders', 'Modules.Favoriterider.Admin'),
            'riderForm' => $riderForm->createView(),
        ]);
    }

    /**
     * Create/Edit form
     *
     * @AdminSecurity("is_granted(['update'], request.get('_legacy_controller'))")
     *
     * @param Request $request
     * @param int $riderId
     *
     * @return Response
     */
    public function editAction(Request $request, $riderId): Response
    {
        $riderFormBuilder = $this->getFormBuilder();
        $riderForm = $riderFormBuilder->getFormFor((int) $riderId);
        $riderForm->handleRequest($request);

        $riderFormHandler = $this->getFormHandler();
        $result = $riderFormHandler->handleFor((int) $riderId, $riderForm);

        if ($result->isSubmitted() && $result->isValid()) {
            $this->addFlash('success', $this->trans('Successful update.', 'Admin.Notifications.Success'));

            return $this->redirectToRoute('admin_favoriterider_riders_index');
        }

        return $this->render('@Modules/favoriterider/views/templates/admin/riders/edit.html.twig', [
            'layoutTitle' => $this->trans('Manage riders', 'Modules.Favoriterider.Admin'),
            'riderForm' => $riderForm->createView(),
        ]);
    }

    /**
     * Delete a rider
     *
     * @AdminSecurity("is_granted(['delete'], request.get('_legacy_controller'))")
     *
     * @param int $riderId
     *
     * @return Response
     */
    public function deleteAction($riderId): Response
    {
        /** @var RiderRepository $repository */
        $repository = $this->get('prestashop.module.favoriterider.repository.rider_repository');

        try {
            $rider = $repository->findOneById($riderId);
        } catch (EntityNotFoundException $e) {
            $rider = null;
        }

        if (null !== $rider) {
            /** @var EntityManagerInterface $em */
            $em = $this->get('doctrine.orm.entity_manager');
            $em->remove($rider);
            $em->flush();

            $this->addFlash(
                'success',
                $this->trans('Successful deletion.', 'Admin.Notifications.Success')
            );
        } else {
            $this->addFlash(
                'error',
                $this->trans(
                    'Cannot find rider %rider%',
                    'Modules.Favoriterider.Admin',
                    ['%rider%' => $riderId]
                )
            );
        }

        return $this->redirectToRoute('admin_favoriterider_riders_index');
    }

    /**
     * Rider Form Builder
     *
     * @return FormBuilder
     */
    private function getFormBuilder()
    {
        return $this->get('prestashop.module.favoriterider.form.identifiable_object.builder.rider_form_builder');
    }

    /**
     * Rider Form Handler
     *
     * @return FormHandler
     */
    private function getFormHandler()
    {
        return $this->get('prestashop.module.favoriterider.form.identifiable_object.handler.rider_form_handler');
    }
}
