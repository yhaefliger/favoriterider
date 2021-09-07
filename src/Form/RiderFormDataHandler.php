<?php
declare(strict_types=1);

namespace PrestaShop\Module\FavoriteRider\Form;

use Doctrine\ORM\EntityManagerInterface;
use PrestaShop\Module\FavoriteRider\Entity\Rider;
use PrestaShop\Module\FavoriteRider\Repository\RiderRepository;
use PrestaShop\Module\FavoriteRider\Uploader\RiderImageUploader;
use PrestaShop\PrestaShop\Core\Form\IdentifiableObject\DataHandler\FormDataHandlerInterface;
use PrestaShop\PrestaShop\Core\Image\Uploader\ImageUploaderInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

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
   * Image uploader
   *
   * @var RiderImageUploader
   */
  private $riderImageUploader;

  /**
   * Class constructor with injected services arguments
   *
   * @param RiderRepository $riderRepository
   * @param EntityManagerInterface $entityManager
   */
  public function __construct(
    RiderRepository $riderRepository,
    EntityManagerInterface $entityManager,
    RiderImageUploader $riderImageUploader
  )
  {
    $this->riderRepository = $riderRepository;
    $this->entityManager = $entityManager;
    $this->riderImageUploader = $riderImageUploader;    
  }

  /**
   * {@inheritDoc}
   */
  public function create(array $data)
  {
    $rider = new Rider();
    $rider->setName($data['name']);
    $rider->setDiscipline($data['discipline']);
    $rider->setVotes(0);
    
    $this->entityManager->persist($rider);
    $this->entityManager->flush();

    $this->uploadRiderImage($rider->getId(), $data['image']);

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
    
    $this->uploadRiderImage($rider->getId(), $data['image']);

    return $rider->getId();   
  }

  /**
   * Image upload handle
   *
   * @param UploadedFile
   * @return string Image name
   */
  private function uploadRiderImage($riderId, $image) {
    /** @var UploadedFile $uploadedFlagImage */
    $uploadedImage = $image;

    if ($uploadedImage instanceof UploadedFile) {
      return $this->riderImageUploader->upload($riderId, $uploadedImage);
    }

    return '';
  }
}