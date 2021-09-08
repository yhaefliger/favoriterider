<?php

namespace PrestaShop\Module\FavoriteRider\Image;

use HelperList;
use ImageManager;
use PrestaShop\Module\FavoriteRider\Entity\Rider;
use PrestaShop\Module\FavoriteRider\Uploader\RiderImageUploader;
use PrestaShop\PrestaShop\Core\Image\ImageProviderInterface;
use PrestaShop\PrestaShop\Core\Image\Parser\ImageTagSourceParserInterface;

/**
 * Provides path for rider image thumbnail
 */
final class RiderImageThumbnailProvider implements ImageProviderInterface
{
    /**
     * @var ImageTagSourceParserInterface
     */
    private $imageTagSourceParser;

    /**
     * @param ImageTagSourceParserInterface $imageTagSourceParser
     */
    public function __construct(
        ImageTagSourceParserInterface $imageTagSourceParser
    ) {
        $this->imageTagSourceParser = $imageTagSourceParser;
    }

    /**
     * {@inheritdoc}
     */
    public function getPath($riderId)
    {
        $pathToImage = Rider::RIDER_IMAGE_PATH . $riderId . '.jpg';

        $imageTag = ImageManager::thumbnail(
            $pathToImage,
            'rider_mini_' . $riderId . '.jpg',
            HelperList::LIST_THUMBNAIL_SIZE
        );

        return $this->imageTagSourceParser->parse($imageTag);
    }
}
