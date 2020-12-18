<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class ActionDashboardController extends Controller
{
    /**
     * @Route("/actions_points", name="actions_points")
     */
    public function ActionsPoints()
    {
       return $this->render('site/actions/actions_points.html.twig');
    }

    /**
     * @Route("/medailles_badges", name="medailles_badges")
     */
    public function MedaillesBadges()
    {
       return $this->render('site/actions/medailles_badges.html.twig');
    }

    /**
     * @Route("/passer_action", name="passer_action")
     */
    public function PasserAction()
    {
       return $this->render('site/actions/page_passer_action.html.twig');
    }
    
    /**
     * @Route("/communaute_action", name="communaute_action")
     */
    public function CommunauteAction()
    {
       return $this->render('site/actions/communaute.html.twig');
    }

}
