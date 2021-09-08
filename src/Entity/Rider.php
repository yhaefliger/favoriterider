<?php
declare(strict_types=1);

namespace PrestaShop\Module\FavoriteRider\Entity;

use Context;
use Doctrine\ORM\Mapping as ORM;
use Link;

/**
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="PrestaShop\Module\FavoriteRider\Repository\RiderRepository")
 */
class Rider
{
    /**
     * Rider image path
     */
    public const RIDER_IMAGE_PATH = _PS_IMG_DIR_.'rider/';

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
        if (in_array($size, ['mini', 'thumb'])) {
            $image_name = $this->getId() . '-' . $size . '.jpg';
        } else {
            $image_name = $this->getId() . '.jpg';
        }
        
        $image_path = implode(DIRECTORY_SEPARATOR, [
            rtrim(self::RIDER_IMAGE_PATH, DIRECTORY_SEPARATOR),
            $image_name
        ]);
        
        if (file_exists($image_path)) {
            return __PS_BASE_URI__ . 'img/rider/' . $image_name;
        }

        return '';
    }
}