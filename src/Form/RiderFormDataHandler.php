<?php
declare(strict_types=1);

namespace PrestaShop\Module\FavoriteRider\Form;

use Doctrine\ORM\EntityManagerInterface;
use PrestaShop\Module\FavoriteRider\Entity\Rider;
use PrestaShop\Module\FavoriteRider\Repository\RiderRepository;
use PrestaShop\PrestaShop\Core\Form\IdentifiableObject\DataHandler\FormDataHandlerInterface;

class RiderFormDataHandler implements FormDataHandlerInterface
{

  /**
   * Rider entity repository
   *
   * @var RiderRepository
   */
  private $riderRepository;

  /**
   * @var EntityManagerInterface
   */
  private $entityManager;

  /**
   * Class constructor with injected services arguments
   *
   * @param RiderRepository $riderRepository
   * @param EntityManagerInterface $entityManager
   */
  public function __construct(
    RiderRepository $riderRepository,
    EntityManagerInterface $entityManager
  )
  {
    $this->riderRepository = $riderRepository;
    $this->entityManager = $entityManager;    
  }

  /**
   * {@inheritDoc}
   */
  public function create(array $data)
  {
    $rider = new Rider();
    $rider->setName($data['name']);
    $rider->setDiscipline($data['discipline']);
    $rider->setImageName('');

    $this->entityManager->persist($rider);
    $this->entityManager->flush();

    return $rider->getId();    
  }

  /** 
   * {@inheritDoc}
   */
  public function update($id, array $data)
  {
    /** @var Rider $rider */
    $rider = $this->riderRepository->findOneById($id);
    $rider->setName($data['name']);
    $rider->setDiscipline($data['discipline']);

    $this->entityManager->flush();

    return $rider->getId();   
  }
}