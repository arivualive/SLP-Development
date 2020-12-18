<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\UserProfil;
use AppBundle\Entity\Achievement;


/**
 * UserProfilAchievement
 *
 * @ORM\Table(name="user_profil_achievement")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserProfilAchievementRepository")
 */
class UserProfilAchievement
{
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="UserProfil", inversedBy="userProfil_Achievements")
     * @ORM\JoinColumn(name="userProfil", referencedColumnName="id")
     */
    private $userProfil;
    
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Achievement", inversedBy="userProfil_Achievements")
     * @ORM\JoinColumn(name="achievement", referencedColumnName="id")
     */
    private $achievement;

    /**
     * @var int
     *
     * @ORM\Column(name="progress", type="integer")
     */
    private $progress;
    
    function __construct() {
        $this->progress = 0;
    }

    
   /**
     * Get UserProfil.
     *
     * @return \AppBundle\Entity\UserProfil
     */  
    function getUserProfil() {
        return $this->userProfil;
    }

    /**
     * Get Achievement.
     *
     * @return \AppBundle\Entity\Achievement
     */  
    function getAchievement() {
        return $this->achivement;
    }
    
    /**
     * Set UserProfil.
     *
     * @param \AppBundle\Entity\UserProfil $userProfil
     *
     * @return UserProfilAchievements
    */
    function setUserProfil($userProfil) {
        $this->userProfil = $userProfil;
    }
    
    /**
     * Set Achievement.
     *
     * @param \AppBundle\Entity\Achievement $achivement
     *
     * @return UserProfilAchievements
    */
    function setAchievement($achivement) {
        $this->achivement = $achivement;
    }

    /**
     * Get progress.
     *
     * @return int
     */  
    function getProgress() {
        return $this->progress;
    }
    
    /**
     * Set progressMax.
     *
     * @param int
     */  
    function setProgress($progress) {
        $this->ifSupElseMax($progress);
    }
    
    /**
     * function ifSupElseMax.
     *
     * @param int
     */  
    function ifSupElseMax($progressInc){
        $newTot= $this->getProgress()+$progressInc;
        if ($newTot <= $this->getAchievement()->getProgressMax())
            $this->progress = $newTot;
        else
            $this->progress = $this->getAchievement()->getProgressMax();
    }
    
    /**
     * function incProgress.
     */ 
    function incProgress(){
        $this->ifSupElseMax(1);
    }
}
