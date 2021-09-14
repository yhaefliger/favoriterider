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

namespace PrestaShop\Module\FavoriteRider\Uploader;

use ImageManager;
use PrestaShop\Module\FavoriteRider\Entity\Rider;
use PrestaShop\PrestaShop\Adapter\Image\Uploader\AbstractImageUploader;
use PrestaShop\PrestaShop\Core\Image\Uploader\Exception\ImageOptimizationException;
use PrestaShop\PrestaShop\Core\Image\Uploader\Exception\ImageUploadException;
use PrestaShop\PrestaShop\Core\Image\Uploader\Exception\MemoryLimitException;
use PrestaShopException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Upload Rider Image
 */
final class RiderImageUploader extends AbstractImageUploader
{
    /**
     * {@inheritdoc}
     */
    public function upload($riderId, UploadedFile $image)
    {
        $this->checkImageIsAllowedForUpload($image);
        $temporaryImageName = tempnam(_PS_TMP_IMG_DIR_, 'PS');

        if (!$temporaryImageName) {
            throw new ImageUploadException('An error occurred while uploading the image. Check your directory permissions.');
        }

        if (!move_uploaded_file($image->getPathname(), $temporaryImageName)) {
            throw new ImageUploadException('An error occurred while uploading the image. Check your directory permissions.');
        }

        // Evaluate the memory required to resize the image: if it's too much, you can't resize it.
        if (!ImageManager::checkImageMemoryLimit($temporaryImageName)) {
            throw new MemoryLimitException('Due to memory limit restrictions, this image cannot be loaded. Increase your memory_limit value.');
        }

        // Copy new image
        if (!ImageManager::resize($temporaryImageName, Rider::IMAGE_PATH . $riderId . '.jpg')) {
            throw new ImageOptimizationException('An error occurred while uploading the image. Check your directory permissions.');
        }

        //admin thumb clear
        if (file_exists(_PS_TMP_IMG_DIR_ . 'rider_mini_' . $riderId . '.jpg')) {
            @unlink(_PS_TMP_IMG_DIR_ . 'rider_mini_' . $riderId . '.jpg');
        }

        $this->createThumbnail($riderId);
    }

    /**
     * Create the thumbnail
     *
     * @param string $original
     *
     * @return bool
     */
    private function createThumbnail($riderId): bool
    {
        $resized = true;
        $filename = Rider::IMAGE_PATH . $riderId . '.jpg';

        try {
            /* Generate 150x150 thumbnail */
            if (isset($_FILES) &&
                count($_FILES) &&
                file_exists($filename)
            ) {
                /*
                 * Squared thumb 150x150
                 */
                $resized &= ImageManager::resize(
                    Rider::IMAGE_PATH . $riderId . '.jpg',
                    Rider::IMAGE_PATH . $riderId . '-mini.jpg',
                    150,
                    150
                );

                /*
                 * Same proportions height based thumbs
                 */
                foreach (Rider::IMAGE_SIZES as $thumbName => $heightThumb) {
                    list($widthOrig, $heightOrig) = getimagesize($filename);
                    $widthThumb = ($heightThumb * $widthOrig) / $heightOrig;

                    $resized &= ImageManager::resize(
                        Rider::IMAGE_PATH . $riderId . '.jpg',
                        Rider::IMAGE_PATH . $riderId . '-' . $thumbName . '.jpg',
                        $widthThumb,
                        $heightThumb
                    );
                }

                return true;
            }
        } catch (PrestaShopException $e) {
            throw new ImageOptimizationException('Unable to resize picture.');
        }
        if (!$resized) {
            throw new ImageOptimizationException('Unable to resize picture.');
        }

        return $resized;
    }
}
