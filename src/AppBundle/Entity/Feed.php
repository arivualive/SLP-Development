<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Feed
 *
 * @ORM\Table(name="feed")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FeedRepository")
 */
class Feed
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
     * @ORM\Column(name="titreContenu", type="string", length=255)
     */
    private $titrecontenu;

    /**
     * @var string
     *
     * @ORM\Column(name="Description", type="text")
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="Categorie", type="string", length=255)
     */
    private $categorie;

    /**
     * @var string
     *
     * @ORM\Column(name="Image", type="string", length=255)
     */
    private $image;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\UserSlp")
     */
    private $user;

    /**
     * @ORM\Column(name="status", type="boolean")
     */
    private $status;

    /**
     * @ORM\Column(name="priorite", type="boolean")
     */
    private $priorite;

    /**
     *@var @ORM\Column(name="update_at", type="datetime" ,nullable=true)
     */
    private $updateAt;


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
     * @return Feed
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
     * Set titrecontenu.
     *
     * @param string $titrecontenu
     *
     * @return Feed
     */
    public function setTitreContenu($titrecontenu)
    {
        $this->titrecontenu = $titrecontenu;

        return $this;
    }

    /**
     * Get titre.
     *
     * @return string
     */
    public function getTitreContenu()
    {
        return $this->titrecontenu;
    }

    /**
     * Set description.
     *
     * @param string $description
     *
     * @return Feed
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
     * Set categorie.
     *
     * @param string $categorie
     *
     * @return Feed
     */
    public function setCategorie($categorie)
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * Get categorie.
     *
     * @return string
     */
    public function getCategorie()
    {
        return $this->categorie;
    }

    /**
     * Set image.
     *
     * @param string $image
     *
     * @return Feed
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
     * Set user.
     *
     * @param \AppBundle\Entity\UserSlp|null $user
     *
     * @return Feed
     */
    public function setUser(\AppBundle\Entity\UserSlp $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user.
     *
     * @return \AppBundle\Entity\UserSlp|null
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Get the value of status
     */ 
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set the value of status
     *
     * @return  self
     */ 
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get the value of priorite
     */ 
    public function getPriorite()
    {
        return $this->priorite;
    }

    /**
     * Set the value of priorite
     *
     * @return  self
     */ 
    public function setPriorite($priorite)
    {
        $this->priorite = $priorite;

        return $this;
    }

    /**
     * Get *@var @ORM\Column(name="update_at", type="datetime" ,nullable=true)
     */ 
    public function getUpdateAt()
    {
        return $this->updateAt;
    }

    /**
     * Set *@var @ORM\Column(name="update_at", type="datetime" ,nullable=true)
     *
     * @return  self
     */ 
    public function setUpdateAt($updateAt)
    {
        $this->updateAt = $updateAt;

        return $this;
    }
}
