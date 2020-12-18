<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\Action;

/**
 * Achievement
 *
 * @ORM\Table(name="achievement")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AchievementRepository")
 */
class Achievement
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
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @var int
     *
     * @ORM\Column(name="progressMax", type="integer")
     */
    private $progressMax;
    
    /**
     * @ORM\OneToMany(targetEntity="AchievementActions", mappedBy="achievement")
     */
    private $actions;
    
    function __construct() {
        $progressMax=1;
    }

    
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
     * @return Achievement
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
     * Get progressMax.
     *
     * @return int
     */  
    function getProgressMax() {
        return $this->progressMax;
    }
    
    /**
     * Get actions.
     *
     * @return \Doctrine\Common\Collections\ArrayCollection|\AppBundle\Entity\Actions[]
     */   
    function getActions() {
        return $this->actions;
    }

    /**
     * Set progressMax.
     *
     * @param int
     */  
    function setProgressMax($progressMax) {
        $this->progressMax = $progressMax;
    }

     
    /**
     * Set actions.
     *
     * @param \Doctrine\Common\Collections\ArrayCollection
     *
     * @return Achievement
    */
    function setActionsLog($actions) {
        $this->actions = $actions;
        return $this;
    }
 
    /**
     * function addAction.
     *
     * @param \AppBundle\Entity\Action $action
     * 
     * @return Achievement 
     */      
    function addAction($action) {
        $this->actions->add($action);
        return $this;
    }

    /**
     * function removeAction.
     *
     * @param \AppBundle\Entity\Action $action
     * 
     * @return Achievement 
     */  
    function removeAction($action) {
        $this->actions->removeElement($action);
        $this->actions = clone $this->actions;
        return $this;
    }

}
