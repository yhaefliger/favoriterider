<?php
declare(strict_types=1);

namespace PrestaShop\Module\FavoriteRider\Form;

use PrestaShop\Module\FavoriteRider\Entity\Rider;
use PrestaShop\Module\FavoriteRider\Repository\RiderRepository;
use PrestaShop\PrestaShop\Core\Form\IdentifiableObject\DataProvider\FormDataProviderInterface;

class RiderFormDataProvider implements FormDataProviderInterface
{
  /**
   * Rider Repository
   *
   * @var RiderRepository
   */
  private $repository;

  /**
   * Class constructor with injected repository
   *
   * @param RiderRepository $repository
   */
  public function __construct(RiderRepository $repository) 
  {
    $this->repository = $repository;
  }

  public function getData($riderId)
  {
    /** @var Rider $rider */
    $rider = $this->repository->findOneById($riderId);
    
    $riderData = [
      'name' => $rider->getName(),
      'discipline' => $rider->getDiscipline(),
      'image_name' => $rider->getImageName(),
    ];

    return $riderData;
  }

  /**
   * {@inheritDoc}
   */
  public function getDefaultData()
  {
    return [
      'name' => '',
      'discipline' => '',
      'image_name' => '',
    ];
  }
}