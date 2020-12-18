<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\UserProfil;
use AppBundle\Entity\Action;

/**
 * UserProfilActions
 *
 * @ORM\Table(name="user_profil_actions")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserProfilActionsRepository")
 */
class UserProfilActions
{
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="UserProfil", inversedBy="userProfil_Actions")
     * @ORM\JoinColumn(name="userProfil", referencedColumnName="id")
     */
    private $userProfil;
    
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Action", inversedBy="userProfil_Actions")
     * @ORM\JoinColumn(name="action", referencedColumnName="id")
     */
    private $action;

    function __construct() {}

    /**
     * Get UserProfil.
     *
     * @return \AppBundle\Entity\UserProfil
     */  
    function getUserProfil() {
        return $this->userProfil;
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
     * Set UserProfil.
     *
     * @param \AppBundle\Entity\UserProfil $userProfil
     *
     * @return UserProfilActions
    */
    function setUserProfil($userProfil) {
        $this->userProfil = $userProfil;
    }
    
    /**
     * Set Action.
     *
     * @param \AppBundle\Entity\Action $action
     *
     * @return UserProfilActions
    */
    function setAction($action) {
        $this->action = $action;
    }


}
