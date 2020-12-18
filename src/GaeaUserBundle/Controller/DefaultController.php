<?php

namespace GaeaUserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/user")
     */
    public function indexAction()
    {
        return $this->render('GaeaUserBundle:Default:index.html.twig');
    }
}
