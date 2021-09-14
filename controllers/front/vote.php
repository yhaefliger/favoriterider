<?php

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
    if(!$id_rider || $id_rider <= 0){
      return $this->redirectWithError(
        $this->trans('Missing rider parameter.', [], 'Modules.Favoriterider.Shop')
      );
    }

    //check rider from repository
    /** @var RiderRepository $riderRepository */
    $riderRepository = $this->context->controller->getContainer()->get('prestashop.module.favoriterider.repository.rider_repository');
    /** @var Rider $rider */
    $rider = $riderRepository->find($id_rider);
    if(!$rider){
      return $this->redirectWithError(
        $this->trans('Rider could not be found. Please try again.', [], 'Modules.Favoriterider.Shop')
      );
    }

    //check previously voted to decrement
    if($this->context->cookie->favorite_rider) {
      $previous_voted_rider = $riderRepository->find($this->context->cookie->favorite_rider);
      if($previous_voted_rider && $previous_voted_rider->getVotes() > 0) {
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
   * @return Response
   */
  protected function redirectWithError(string $error): Response
  {
    $this->errors[] = $error;
    return $this->redirectWithNotifications($this->redirect_after);
  }
}