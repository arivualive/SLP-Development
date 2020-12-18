<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use AppBundle\Entity\UserSlp;
use AppBundle\Entity\Miniprofile;
use AppBundle\Entity\UserProfilActions;
use AppBundle\Entity\UserProfilAchievement;
use AppBundle\Entity\Action;

/**
 * UserProfil
 *
 * @ORM\Table(name="user_profil")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserProfilRepository")
 */
class UserProfil
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
     * @ORM\OneToOne(targetEntity="UserSlp", mappedBy="userProfil")
     * @ORM\JoinColumn(name="userSlp_id", referencedColumnName="id")
     */
    private $user;
  
    /**
     * @ORM\OneToOne(targetEntity="Miniprofile", mappedBy="userProfil")
     * @ORM\JoinColumn(name="min_id", referencedColumnName="id")
     */
    private $miniprofil;
    
     /**
     * @var int
     *
     * @ORM\Column(name="pointsTot", type="integer")
     */
    private $pointsTot;  
    
    /**
     * @ORM\OneToMany(targetEntity="UserProfilActions", mappedBy="userProfil")
     */
    private $actionsLog;   
    
    /**
     * @ORM\OneToMany(targetEntity="UserProfilAchievement", mappedBy="userProfil")
     */
    private $achievement;
    
    function __construct() {
        $this->achievement = new ArrayCollection();
        $this->actionsLog = new ArrayCollection();
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
     * Get user.
     *
     * @return \AppBundle\Entity\UserSlp
     */   
    function getUser() {
        return $this->user;
    }

    /**
     * Get Miniprofil.
     *
     * @return \AppBundle\Entity\Miniprofile
     */   
    function getMiniprofil() {
        return $this->miniprofil;
    }
    
    /**
     * Get pointstot.
     *
     * @return int
     */
    function getPointsTot() {
        return $this->pointsTot;
    }

    /**
     * Get actionsLog.
     *
     * @return \Doctrine\Common\Collections\ArrayCollection|\AppBundle\Entity\UserProfilActions[]
     */   
    function getActionsLog() {
        return $this->actionsLog;
    }
    
    /**
     * Set User.
     *
     * @param \AppBundle\Entity\UserSlp $user
     *
     * @return UserProfil
    */
    function setUser($user) {
        $this->user = $user;
        return $this;
    }

    /**
     * Set Miniprofil.
     *
     * @param \AppBundle\Entity\Miniprofile $miniprofil
     *
     * @return UserProfil
    */
    function setMiniprofil($miniprofil) {
        $this->miniprofil = $miniprofil;
        return $this;
    }
    
    /**
     * Set pointsTot.
     *
     * @param int $pointsTot
    */
    function setPointsTot($pointsTot) {
        $this->pointsTot = $pointsTot;
    }
    
    /**
     * function addPoints.
     *
     * @param int $points
    */
    function addPoints($points) {
        $this->pointsTot += $points;
    }
        
    /**
     * Set actionsLog.
     *
     * @param \Doctrine\Common\Collections\ArrayCollection
     *
     * @return UserProfil
    */
    function setActionsLog($actionsLog) {
        $this->actionsLog = $actionsLog;
        return $this;
    }
 
    /**
     * function addAction.
     *
     * @param \AppBundle\Entity\Action $action
     * 
     * @return UserProfil 
     */      
    function addAction($action) {
        $this->actionsLog->add($action);
        return $this;
    }

    /**
     * function removeAction.
     *
     * @param \AppBundle\Entity\Action $action
     * 
     * @return UserProfil 
     */  
    function removeAction($action) {
        $this->actionsLog->removeElement($action);
        $this->actionsLog = clone $this->actionsLog;
        return $this;
    }

    /**
     * Get achievements.
     *
     * @return \Doctrine\Common\Collections\ArrayCollection|\AppBundle\Entity\Achievement[]
     */   
    function getAchievements() {
        return $this->achievement;
    }
      
    /**
     * Set achievements.
     *
     * @param \Doctrine\Common\Collections\ArrayCollection $achivements
     *
     * @return UserProfil
    */
    function setAchievements($achivements) {
        $this->achievement = $achivements;
        return $this;
    }
 
    /**
     * function addAchievement.
     *
     * @param \AppBundle\Entity\UserProfilAchievement $achievement
     * 
     * @return UserProfil 
     */      
    function addAchievement($achievement) {
        $this->achievement->add($achievement);
        return $this;
    }

    /**
     * function removeAchievement.
     *
     * @param \AppBundle\Entity\UserProfilAchievement $achievement
     * 
     * @return UserProfil 
     */  
    function removeAchievement($achievement) {
        $this->achievement->removeElement($achievement);
        $this->achievement = clone $this->achievement;
        return $this;
    }
}
