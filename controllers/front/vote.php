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

use PrestaShop\Module\FavoriteRider\Entity\Rider;
use PrestaShop\Module\FavoriteRider\Repository\RiderRepository;
use Symfony\Component\HttpFoundation\Response;

/**
 * Front controller to process user vote!
 */
class FavoriteRiderVoteModuleFrontController extends ModuleFrontController
{
    /**
     * Post process action
     *
     * @return void
     */
    public function postProcess()
    {
        $id_rider = (int) Tools::getValue('id_rider');
        //redirect to previous page or home if no referer which should not occur
        $this->redirect_after = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : null;
        /** @var EntityManagerInterface $entityManager */
        $entityManager = $this->container->get('doctrine.orm.entity_manager');

        //check submited rider post param
        if (!$id_rider || $id_rider <= 0) {
            return $this->redirectWithError($this->trans('Missing rider parameter.', [], 'Modules.Favoriterider.Shop'));
        }

        //check rider from repository
        /** @var RiderRepository $riderRepository */
        $riderRepository = $this->context->controller->getContainer()->get('prestashop.module.favoriterider.repository.rider_repository');
        /** @var Rider $rider */
        $rider = $riderRepository->find($id_rider);
        if (!$rider) {
            return $this->redirectWithError($this->trans('Rider could not be found. Please try again.', [], 'Modules.Favoriterider.Shop'));
        }

        //check previously voted to decrement
        if ($this->context->cookie->favorite_rider) {
            $previous_voted_rider = $riderRepository->find($this->context->cookie->favorite_rider);
            if ($previous_voted_rider && $previous_voted_rider->getVotes() > 0) {
                $previous_voted_rider->setVotes($previous_voted_rider->getVotes() - 1);
            }
        }

        //increment current rider votes
        $rider->setVotes($rider->getVotes() + 1);

        $entityManager->flush();

        $this->context->cookie->favorite_rider = $rider->getId();

        $this->success[] = $this->trans('Thanks for your vote! Your vote for the rider %s has been added.', [$rider->getName()], 'Modules.Favoriterider.Shop');

        return $this->redirectWithNotifications($this->redirect_after);
    }

    /**
     * Redirect with error message
     *
     * @param string $error
     *
     * @return Response
     */
    protected function redirectWithError(string $error): Response
    {
        $this->errors[] = $error;

        return $this->redirectWithNotifications($this->redirect_after);
    }
}
