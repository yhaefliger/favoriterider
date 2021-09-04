<?php
declare(strict_types=1);

namespace PrestaShop\Module\FavoriteRider\Entity;

/**
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="PrestaShop\Module\FavoriteRider\Repository\RiderRepository")
 */
class Rider
{

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
     * @var string
     * 
     * @ORM\Column(type="string")
     */
    private $imageName;
    

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
     * Get the value of imageName
     *
     * @return  string
     */ 
    public function getImageName(): string
    {
        return $this->imageName;
    }

    /**
     * Set the value of imageName
     *
     * @param  string  $imageName
     */ 
    public function setImageName(string $imageName): void
    {
        $this->imageName = $imageName;
    }
}