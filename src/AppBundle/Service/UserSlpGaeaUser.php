<?php

namespace AppBundle\Service;

use GaeaUserBundle\Entity\User;
use AppBundle\Entity\UserSlp;
use AppBundle\Repository\UserSlpRepository;
// use GaeaUserBundle\Entity\User;
// use AppBundle\Entity\UserSlp;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UserSlpGaeaUser extends Controller
{
    // EXEMPLE DE FONCTION CAR POUR CE QUI SUIT LE REPO LE FAIT DÉJÀ :
    public function findOneByGaeaUser($gaeaUserId)
    {
         $gaeaUserId = $this->getUser()->getId();
        $userSlp = $this->getDoctrine()->getRepository('AppBundle:UserSlp')->findOneBy(array('gaeaUserId' => $gaeaUserId));

        return $userSlp;
    }
}