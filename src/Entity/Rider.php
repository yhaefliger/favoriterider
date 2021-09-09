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

namespace PrestaShop\Module\FavoriteRider\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="PrestaShop\Module\FavoriteRider\Repository\RiderRepository")
 */
class Rider
{
    /**
     * Rider image path
     */
    public const IMAGE_PATH = _PS_IMG_DIR_.'rider/';

    /**
     * Rider images sizes generated
     */
    public const IMAGE_SIZES = [
        'sm' => 60,
        'md' => 120,
        'lg' => 240,
        'xl' => 500,
    ];

    /**
     * @var int
     * 
     * @ORM\Id
     * @ORM\Column(name="id_rider", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * Rider name
     *
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * Rider discipline (freestyle/alpin/...)
     *
     * @var string
     * 
     * @ORM\Column(type="string")
     */
    private $discipline;

    /**
     * Number of votes
     *
     * @var int
     * 
     * @ORM\Column(type="integer")
     */
    private $votes;
    

    /**
     * Get the value of id
     *
     * @return  int
     */ 
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @param  int  $id
     */ 
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * Get rider name
     *
     * @return  string
     */ 
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set rider name
     *
     * @param  string  $name  Rider name
     */ 
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * Get rider discipline (freestyle/alpin/...)
     *
     * @return  string
     */ 
    public function getDiscipline(): string
    {
        return $this->discipline;
    }

    /**
     * Set rider discipline (freestyle/alpin/...)
     *
     * @param  string  $discipline  Rider discipline (freestyle/alpin/...)
     */ 
    public function setDiscipline(string $discipline): void
    {
        $this->discipline = $discipline;
    }


    /**
     * Get number of votes
     *
     * @return  int
     */ 
    public function getVotes(): int
    {
        return $this->votes;
    }

    /**
     * Set number of votes
     *
     * @param  int  $votes  Number of votes
     */ 
    public function setVotes(int $votes): void
    {
        $this->votes = $votes;
    }

    /**
     * Get Rider image path
     *
     * @param sring $size (mini, thumb, default)
     * 
     * @return string
     */
    public function getImageUrl(string $size = 'default'): string
    {
        if (in_array($size, self::IMAGE_SIZES)) {
            $image_name = $this->getId() . '-' . $size . '.jpg';
        } else {
            $image_name = $this->getId() . '.jpg';
        }
        
        $image_path = implode(DIRECTORY_SEPARATOR, [
            rtrim(self::IMAGE_PATH, DIRECTORY_SEPARATOR),
            $image_name
        ]);
        
        if (file_exists($image_path)) {
            return __PS_BASE_URI__ . 'img/rider/' . $image_name;
        }

        return '';
    }

    /**
     * Return array of all images size
     *
     * @return array
     */
    public function getAllImages(): array
    {
        $images = [];
        
        foreach (array_keys(Rider::IMAGE_SIZES) as $size) {
            $images[$size] = $this->getImageUrl($size);
        }
        $images['default'] = $this->getImageUrl();

        return $images;
    }
}