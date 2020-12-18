<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\Action;
use AppBundle\Entity\Achievement;

/**
 * AchievementActions
 *
 * @ORM\Table(name="achievement_actions")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AchievementActionsRepository")
 */
class AchievementActions
{
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Achievement", inversedBy="achievementActions")
     * @ORM\JoinColumn(name="achievement", referencedColumnName="id")
     */
    private $achievement;
    
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Action", inversedBy="achievementActions")
     * @ORM\JoinColumn(name="action", referencedColumnName="id")
     */
    private $action;

    function __construct() {}

    /**
     * Get Achievement.
     *
     * @return \AppBundle\Entity\Achievement
     */  
    function getAchievement() {
        return $this->achievement;
    }

    /**
     * Get Action.
     *
     * @return \AppBundle\Entity\Action
     */  
    function getAction() {
        return $this->action;
    }
    
    /**
     * Set Achievement.
     *
     * @param \AppBundle\Entity\Achievement $achievement
     *
     * @return AchievementActions
    */
    function setAchievement($achievement) {
        $this->achievement = $achievement;
    }
    
    /**
     * Set Action.
     *
     * @param \AppBundle\Entity\Action $action
     *
     * @return AchievementActions
    */
    function setAction($action) {
        $this->action = $action;
    }

}
