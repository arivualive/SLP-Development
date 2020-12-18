<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Personalisation;
use AppBundle\Entity\UserSlp;
use AppBundle\Form\FormValidationType; 
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;


class PersonalisationAPIController extends Controller
{
    /**                                                                                   
     * @Route("/upload", name="dashboard_personalisation_upload", methods={"POST"})
     * 
     */
    public function ajaxAction(Request $request)
    {
        $data = $request->request->all();
        $cover = $request->request->get('coverData');
        $avatar = $request->request->get('avatarData');         

        if($request->isXmlHttpRequest()){
            $userSlpRepo = $this->getDoctrine()->getRepository(UserSlp::class);          
            $UserConecte = $userSlpRepo->findOneByGaeaUserId($this->getUser()->getId());            
            $userSlp = $userSlpRepo->findOneBy(array('id' => $UserConecte) );
            $currentUser = $userSlp->getId();
            $currentPersonalisation = $userSlp->getPersonalisationId();

            if($currentUser) {
                // Trouver la personalisation par son ID
                $personalisation =  $this->getDoctrine()->getRepository(Personalisation::class)->findOneBy(array('id' => $currentPersonalisation));

                // Obtenir l'instance du gestionnaire de l'entité
                $em=$this->getDoctrine()->getManager();

                
                if($currentPersonalisation === null) {
                    // Création de la personalisation si aucune trouvée
                    $personalisation = new personalisation();
                    switch ($data) {
                        case $cover : $personalisation->setImage($cover); break;
                        case $avatar : $personalisation->setAvatar($avatar); break;
                    }
                    $userSlp->setPersonalisationId($personalisation);
                } 
                
                // Mise à jour Image
                foreach($data as $key => $value) {
                    if($value != "null") {
                        switch ($value) {
                            case $cover : $personalisation->setImage($cover); break;
                            case $avatar : $personalisation->setAvatar($avatar); break;
                        }
                    }
                } 
                $em->persist($personalisation);
                $em->flush();
                
            }
        };
        return new JsonResponse("This is Ajax");
    }
}