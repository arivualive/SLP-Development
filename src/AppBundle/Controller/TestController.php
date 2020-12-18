<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;


class TestController extends Controller {


    /**
     * @Route("/testController", name="test_controller")
     *
     */
    public function findALLByTable(){

        $repositoryTheme = $this->getDoctrine()->getManager()->getRepository('AppBundle:Theme_budget');
        $listTheme = $repositoryTheme->findAll();

        $repositoryCategories = $this->getDoctrine()->getManager()->getRepository('AppBundle:Categories');
        $listCategories =  $repositoryCategories->findAll();

        $repositorySousCategories = $this->getDoctrine()->getManager()->getRepository('AppBundle:SousCategories');
        $listSousCategories =  $repositorySousCategories->findAll();

        $repositoryProduct = $this->getDoctrine()->getManager()->getRepository('AppBundle:Product');
        $listProduct = $repositoryProduct->findAll();


        return $this->render('site/test.html.twig', [
            'listTheme' => $listTheme,
            'listCategories' => $listCategories,
            'listSousCategories' => $listSousCategories,
            'listProduct' => $listProduct
        ]);
    }


}


