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

namespace PrestaShop\Module\FavoriteRider\Form;

use Doctrine\ORM\EntityManagerInterface;
use PrestaShop\Module\FavoriteRider\Entity\Rider;
use PrestaShop\Module\FavoriteRider\Repository\RiderRepository;
use PrestaShop\Module\FavoriteRider\Uploader\RiderImageUploader;
use PrestaShop\PrestaShop\Core\Form\IdentifiableObject\DataHandler\FormDataHandlerInterface;
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
  ) {
        $this->riderRepository = $riderRepository;
        $this->entityManager = $entityManager;
        $this->riderImageUploader = $riderImageUploader;
    }

    /**
     * {@inheritDoc}
     */
    public function create(array $data): int
    {
        $rider = new Rider();
        $rider->setName($data['name']);
        $rider->setDiscipline($data['discipline']);
        $rider->setVotes(0);

        $this->entityManager->persist($rider);
        $this->entityManager->flush();

        if ($data['image'] instanceof UploadedFile) {
            $this->riderImageUploader->upload($rider->getId(), $data['image']);
        }

        return $rider->getId();
    }

    /**
     * Undocumented function
     *
     * @param int $id
     * @param array $data
     *
     * @return int
     */
    public function update($id, array $data): int
    {
        /** @var Rider $rider */
        $rider = $this->riderRepository->findOneById($id);
        $rider->setName($data['name']);
        $rider->setDiscipline($data['discipline']);

        $this->entityManager->flush();
        if ($data['image'] instanceof UploadedFile) {
            $this->riderImageUploader->upload($rider->getId(), $data['image']);
        }

        return $rider->getId();
    }
}
