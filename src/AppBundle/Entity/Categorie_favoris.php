<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Categorie_favoris
 *
 * @ORM\Table(name="categorie_favoris")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Categorie_favorisRepository")
 */
class Categorie_favoris
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="Titre", type="string", length=255)
     */
    private $titre;

    /**
     * @var string
     *
     * @ORM\Column(name="Description", type="text")
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="Image", type="string", length=255)
     */
    private $image;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Favoris")
     * @ORM\JoinColumn(nullable=false)
     */
    private $favoris;




    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set titre.
     *
     * @param string $titre
     *
     * @return Categorie_favoris
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;

        return $this;
    }

    /**
     * Get titre.
     *
     * @return string
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * Set description.
     *
     * @param string $description
     *
     * @return Categorie_favoris
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set image.
     *
     * @param string $image
     *
     * @return Categorie_favoris
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image.
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set favoris.
     *
     * @param \AppBundle\Entity\Favoris $favoris
     *
     * @return Categorie_favoris
     */
    public function setFavoris(\AppBundle\Entity\Favoris $favoris)
    {
        $this->favoris = $favoris;

        return $this;
    }

    /**
     * Get favoris.
     *
     * @return \AppBundle\Entity\Favoris
     */
    public function getFavoris()
    {
        return $this->favoris;
    }


}
