<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\ActionCategory;

/**
 * Action
 *
 * @ORM\Table(name="action")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ActionRepository")
 */
class Action
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var int|null
     *
     * @ORM\Column(name="points", type="integer", nullable=true)
     */
    private $points;

    /**
     * @ORM\OneToMany(targetEntity="ActionCategory", mappedBy="action")
     * @ORM\JoinColumn(name="actCat_id", referencedColumnName="id")
     */
    private $category;
    
    public function __construct() {}
    
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
     * Set name.
     *
     * @param string $name
     *
     * @return Action
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set points.
     *
     * @param int|null $points
     *
     * @return Action
     */
    public function setPoints($points = null)
    {
        $this->points = $points;

        return $this;
    }

    /**
     * Get points.
     *
     * @return int|null
     */
    public function getPoints()
    {
        return $this->points;
    }
    
    /**
     * Get category.
     * 
     * @return \AppBundle\Entity\ActionCategory $category
     */
    function getCategory() {
        return $this->category;
    }
    
    /**
     * Set category.
     *
     * @param \AppBundle\Entity\ActionCategory $category
     * 
     * @return Action|null
     */
    function setCategory($category) {
        $this->category = $category;
        return $this;
    }


}
