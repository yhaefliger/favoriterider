<?php
declare(strict_types=1);

namespace PrestaShop\Module\FavoriteRider\Uploader;

use FavoriteRider;
use ImageManager;
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

    public const RIDER_IMAGE_PATH = _PS_IMG_DIR_.'rider/';

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
        if (!ImageManager::resize($temporaryImageName, self::RIDER_IMAGE_PATH . $riderId . '.jpg')) {
            throw new ImageOptimizationException('An error occurred while uploading the image. Check your directory permissions.');
        }
        
        $this->createThumbnail($riderId);
    }

    /**
     * Create the thumbnail
     *
     * @param string $original
     * @return bool
     */
    private function createThumbnail($riderId)
    {
        $resized = true;
        $filename = self::RIDER_IMAGE_PATH . $riderId . '.jpg';

        try {
            /* Generate 150x150 thumbnail */
            if (isset($_FILES) &&
                count($_FILES) &&
                file_exists($filename)
            ) {
                /**
                 * Squared thumb 150x150
                 */
                $resized &= ImageManager::resize(
                    self::RIDER_IMAGE_PATH . $riderId . '.jpg',
                    self::RIDER_IMAGE_PATH . $riderId . '-mini.jpg',
                    150,
                    150
                );

                /**
                 * Same proportions but 300 height thumb
                 */
                $heightThumb = 300;
                list($widthOrig, $heightOrig) = getimagesize($filename);
                $widthThumb = ($heightThumb * $widthOrig) / $heightOrig;

                 $resized &= ImageManager::resize(
                    self::RIDER_IMAGE_PATH . $riderId . '.jpg',
                    self::RIDER_IMAGE_PATH . $riderId . '-thumb.jpg',
                    $widthThumb,
                    $heightThumb
                );
                
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