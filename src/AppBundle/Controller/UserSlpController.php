<?php

namespace AppBundle\Controller;

use DateTime;
use AppBundle\Entity\Feed;
use AppBundle\Entity\UserSlp;
use AppBundle\Entity\UserProfil;
use AppBundle\Entity\ProgressManger;
use AppBundle\Entity\ProgressPsycho;
use AppBundle\Entity\SurveyProgress;
use AppBundle\Service\UserLoggedTest;

use QuestionBundle\Entity\Question;
use QuestionBundle\Entity\Answer;
use QuestionBundle\Entity\Manger;
use QuestionBundle\Entity\Survey;
use QuestionBundle\Entity\Answer_Free;
use QuestionBundle\Entity\Sub_question;
use QuestionBundle\Entity\Answer_choice;
use QuestionBundle\Entity\Theme;

use AppBundle\Repository\UserSlpRepository;
use AppBundle\Repository\ProgressMangerRepository;

use QuestionBundle\Repository\AnswerRepository;
use QuestionBundle\Repository\QuestionRepository;
use QuestionBundle\Repository\Answer_choiceRepository;
use QuestionBundle\Repository\ThemeRepository;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Cookie;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Userslp controller.
 *
 * @Route("userslp")
 */
class UserSlpController extends Controller
{
    /**
     * Creates show the main page.
     *
     * @Route("/eco-profile", name="userslp_portail")
     * @Method({"GET", "POST"})
     */
   public function portailAction()
    {
        if (!$auth_checker = $this->get('security.authorization_checker')->isGranted("ROLE_USER")) {
            return $this->redirectToRoute('fos_user_security_login');
        }

        $questionMangerRepo = $this->getDoctrine()->getRepository(Manger::class);
        $userSlpRepo = $this->getDoctrine()->getRepository(UserSlp::class);
        $surveyProgressRepo = $this->getDoctrine()->getRepository(SurveyProgress::class);
        $questionRepo = $this->getDoctrine()->getRepository(Question::class);
        $surveyRepo = $this->getDoctrine()->getRepository(Survey::class);
        $answerRepo = $this->getDoctrine()->getRepository(Answer::class);
        $answerChoiceRepo = $this->getDoctrine()->getRepository(Answer_choice::class);
        $manger = $questionMangerRepo->findAll();

        $userSlp = null;
        $questions = null;
        $form = 1;
        $done = 0;
        $lastQuestionNumber = 0;
        $numberOfQuestion = 29;
        $date = new \DateTime();
        $answers = [];

        if($this->getUser() != null) {
            $currentUserSlp = $userSlpRepo->findOneByGaeaUserId($this->getUser()->getId());
            $profileSurvey = $surveyRepo->findOneById("1");
            if ($currentUserSlp) {
                $userSlp = $currentUserSlp;

                //ajout d'un last login pour l'utilisateur courant
                $entityManager = $this->getDoctrine()->getManager();
                $currentUserSlp->setLastLogin($date);
                $entityManager->flush();

                //récupération du suivi du questionnaire Socio-éco de l'utilisateur
                $surveyProgress = $surveyProgressRepo->findOneBy(["user" => $currentUserSlp, "survey" => $profileSurvey]);


                if (isset($surveyProgress)) {
                    $done = $surveyProgress->getDone();
                    if ($surveyProgress->getLastAnwseredQuestion() instanceof Sub_question ) {
                        $lastQuestionNumber = $surveyProgress->getLastAnwseredQuestion()->getMasterQuestion()->getNumber();
                    } else {
                        $lastQuestionNumber = $surveyProgress->getLastAnwseredQuestion()->getNumber();
                    }
                    if ($done)  {
                        $questions = [];
                        $form = 0;
                    } else {
                        $answers = $answerRepo->findByUserSlp($currentUserSlp, 1);
                        $lastquestionAnswer = $answers[sizeof($answers)-1]->getQuestion();
                        $answerChoice = $answerChoiceRepo->getSubQ($currentUserSlp, $lastquestionAnswer);

                        $questions = $questionRepo->findQuestionWithoutSubBySurvey($profileSurvey);
                        $form = 1;
                    }
                } else {
                    $questions = $questionRepo->findQuestionWithoutSubBySurvey($profileSurvey);
                }
            }
        }
        /*
            done: false(0) si questionnaire en cours, true(1) si terminé
            form: false(0) si questionnaire fini, true(1) questionnaire en cours

            combinaison
            form
    Done            0                                               1
            0       X                                               Questionnaire répondu à 100%
            1       reprise à la dernière question non répondu      X
        */
        /*for ($questions as $question) {

            $nb ++;
        }
        if ($numberOfQuestion > $nb) {
            $surveyCompleted = false;
        } else {
            $surveyCompleted = true;
        }*/

        //$this->isSurveyCompleted();
//        dump($userSlp);
        $id = null;
        if ($userSlp != null){
            $id = $userSlp->getId();
            $userSlp = $this->controlCookie($userSlp);
            $response = $this->iniCookie($id);
            $response->sendHeaders();
        }  
        return $this->render('userslp/profilegaea.html.twig', array(
            'userslp' => $userSlp,
            'form' => $form,
            'questionsSocio' => $questions,
            'manger' => $manger,
            'done' => $done,
            'lastquestionnumber' => $lastQuestionNumber,
            'numberOfQuestion' => $numberOfQuestion,
            'answers' => $answers
        ));
    }
    
    
    /*
     * function controlCookie
     *  vérifie si le cookie éxiste est que le nombre de points n'est pas égale 
     *  à 0 et que l'utilisateur envoyer dans la méthode par le biais du paramètre
     *  $userSlp est le même que celui dont l'ID est stocker dans le cookie. 
     *  Une fois ce test passé, appel une méthode pour incrémenter les points de
     *  l'utilisateur.
     */
    public function controlCookie(\AppBundle\Entity\UserSlp $userSlp) {
        if (isset($_COOKIE['userSLP'])){
            $userdata = explode(':',$_COOKIE['userSLP']);
            $id = $userdata[0];
            $points = $userdata[1]; 
            if (($userSlp->getId() == $id)) $userSlp = $this->addPointsIfExist($userSlp, $points);
            $this->iniCookie($userSlp->getId());
        }
        return $userSlp;
    }
    
    /*
     * function addPointsIfExist
     *  ajoute les points contenu dans le paramètre $points 
     *  pour l'utilisateur SLP passé en paramètre ($userSlp).
     */
    public function addPointsIfExist(\AppBundle\Entity\UserSlp $userSlp, $points){
        $entityManager = $this->getDoctrine()->getManager();
        if (($points > 0) && ($userSlp->getProfil() != null))
            $userSlp->getProfil()->addPoints($points);
        else if ($userSlp->getProfil() == null) {
            $userSlp = $this->initProfilForUserIfNone($userSlp);
            if ($points > 0) $this->addPointsIfExist ($userSlp, $points);
        }
        $entityManager->flush();
        return $userSlp;
    }
    
      /*
     * function initProfilForUserIfNone
     *  initialise le profile utilisateur si l'utilisateur n'en a pas. 
     */  
    public function initProfilForUserIfNone(\AppBundle\Entity\UserSlp $userSlp){
        if ($userSlp->getProfil() == null){
            $entityManager = $this->getDoctrine()->getManager();
            $profil = new UserProfil();
            $profil->setUser($userSlp);
            $profil->setPointsTot(0);
            $userSlp->setProfil($profil);
            $entityManager->persist($profil);
            $entityManager->flush();
        }
        return $userSlp;
    }
    
    /*
     * function iniCookie
     *  initialise un cookie pour l'utilisateur SLP dans l'ID est passé en  
     *  paramètre $userslpID.
     */
    public function iniCookie($userslpID){
        $response = new Response();
        $value = $userslpID.':0';
        $cookie = new Cookie("userSLP",$value, 0, "/", "sustlivprogram.org");
        $response->headers->setCookie($cookie);
        
        return $response;
    }

    public function isSurveyCompleted() {
        $userSlpRepo = $this->getDoctrine()->getRepository(UserSlp::class);
        $currentUserSlp = $userSlpRepo->findOneByGaeaUserId($this->getUser()->getId());
        $answers = $userSlpRepo->findAllAnswers($currentUserSlp);
        /*for ($answer in $answers) {
            $nbQ = $nbQ ++;
        }
        if($nbQ > 26) {
            $surveyCompleted = false;
        } else {
            $surveyCompleted = true;
        }
        return $surveyCompleted;*/
    }
/*  ****************************************************************************************************************
    ***********************Socio*********************************************************************************** */

    /**
     * @Route("/socio-questionnaire", name="userslp_socio")
     */
    public function SocioAction()
    {
        $questionMangerRepo = $this->getDoctrine()->getRepository(Manger::class);
        $userSlpRepo = $this->getDoctrine()->getRepository(UserSlp::class);
        $surveyProgressRepo = $this->getDoctrine()->getRepository(SurveyProgress::class);
        $questionRepo = $this->getDoctrine()->getRepository(Question::class);
        $surveyRepo = $this->getDoctrine()->getRepository(Survey::class);
        $answerRepo = $this->getDoctrine()->getRepository(Answer::class);
        $answerChoiceRepo = $this->getDoctrine()->getRepository(Answer_choice::class);
        $ThemeRepo = $this->getDoctrine()->getRepository(Theme::class);

        $manger = $questionMangerRepo->findAll();

        $userSlp = null;
        $questions = null;
        $form = 1;
        $done = 0;
        $lastQuestionNumber = 0;
        $numberOfQuestion = 29;
        $answers = [];

        if($this->getUser() != null) {
            $currentUserSlp = $userSlpRepo->findOneByGaeaUserId($this->getUser()->getId());
            $profileSurvey = $surveyRepo->findOneById("1");
            if ($currentUserSlp) {
                $userSlp = $currentUserSlp;
                
                //récupération du suivi du questionnaire Socio-éco de l'utilisateur
                $surveyProgress = $surveyProgressRepo->findOneBy(["user" => $currentUserSlp, "survey" => $profileSurvey]);


                if (isset($surveyProgress)) {

                    $done = $surveyProgress->getDone();

                    if ($surveyProgress->getLastAnwseredQuestion() instanceof Sub_question ) {
                        $lastQuestionNumber = $surveyProgress->getLastAnwseredQuestion()->getMasterQuestion()->getNumber();
                    } else {
                        $lastQuestionNumber = $surveyProgress->getLastAnwseredQuestion()->getNumber();
                    }
                    if ($done)  {
                        $questions = [];
                        $form = 0;
                    } else {
                        $answers = $answerRepo->findByUserSlp($currentUserSlp, 1);
                        
                        $lastquestionAnswer = $answers[sizeof($answers)-1]->getQuestion();
                        $answerChoice = $answerChoiceRepo->getSubQ($currentUserSlp, $lastquestionAnswer);
//                        if($lastQuestionNumber==28){
//                            if($answerChoice->getChoices()->getValues()[0]->getName()=="Omnivore"){
//                                $done = true;
//                                $form = 0;
//                                $questions = [];
//                            }
//                        }else{
//                            $questions = $questionRepo->findQuestionWithoutSubBySurvey($profileSurvey, $lastQuestionNumber);
//                            $form = 1;
//                        }
                        $questions = $questionRepo->findQuestionWithoutSubBySurvey($profileSurvey, $lastQuestionNumber);
                        $form = 1;
                    }
                } else {
                    $questions = $questionRepo->findQuestionWithoutSubBySurvey($profileSurvey, $lastQuestionNumber);
                }
            }
        }

        $themes = $ThemeRepo->findBy(array('id'=>array(1,2,3,4,5,6)));
        $surveyProgress = $surveyProgressRepo->findOneBy(["user" => $currentUserSlp, "survey" => $profileSurvey]);

        return $this->render('userslp/profileSocio.html.twig', array(
            'userslp' => $userSlp,
            'formSocio' => $form,
            'questionsSocio' => $questions,
            'themes' => $themes,
            'surveyProgress' => $surveyProgress,
            'answers' => $answers
      ));

    }

    /**
     * @Route("/Done_socio", name="done_socio")
     */
    public function doneSocio() {
        $userSlpRepo = $this->getDoctrine()->getRepository(UserSlp::class);
        $surveyProgressRepo = $this->getDoctrine()->getRepository(SurveyProgress::class);
        $surveyRepo = $this->getDoctrine()->getRepository(Survey::class);

        $surveyProgress = $surveyProgressRepo->findOneBy([
            "user" =>  $userSlpRepo->findOneByGaeaUserId($this->getUser()->getId()), "survey" => $surveyRepo->findOneById("1")
        ]);

        $surveyProgress->setDone(1);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($surveyProgress);
        $entityManager->flush();


        return $this->redirectToRoute('site_dashboard');

    }

/*  ****************************************************************************************************************
    ***********************PSYCHO*********************************************************************************** */
    /**
     * @Route("/psycho-questionnaire", name="userslp_psycho")
     */
    public function psychoAction()
    {

        $userSlpRepo = $this->getDoctrine()->getRepository(UserSlp::class);
        $ProgressPsychoRepo = $this->getDoctrine()->getRepository(SurveyProgress::class);
        $surveyRepo = $this->getDoctrine()->getRepository(Survey::class);
        $questionPsychoRepo = $this->getDoctrine()->getRepository(Question::class);
        $UserConecte = $userSlpRepo->findOneByGaeaUserId($this->getUser()->getId());
        $Sondage = $surveyRepo->findOneById("3");
        $ProgressPsycho = $ProgressPsychoRepo->findOneBy(["user" => $UserConecte, "survey" => $Sondage]);

        if (isset($ProgressPsycho)) {
            $done = $ProgressPsycho->getDone();
            if ($ProgressPsycho->getLastAnwseredQuestion() instanceof Sub_question) {
                 $DerniereQuestion = $ProgressPsycho->getLastAnwseredQuestion()->getMasterQuestion()->getNumber();
            } else {
                $DerniereQuestion = $ProgressPsycho->getLastAnwseredQuestion()->getNumber();
                 }

        }else{
              $done = 0;
              $DerniereQuestion = 0;
        }

          if ($UserConecte == null)
        {
            $userSlp = null;
            $questions = null;
            $formPsycho = null;
        }else{
             $userSlp = $UserConecte;
            if ($done)
            {
                $questions = [];
                $formPsycho = 0;

            } else {

                $questions = $questionPsychoRepo->findQuestionWithoutSubBySurvey($Sondage, $DerniereQuestion);
                $formPsycho = 1;
            }
        }


        return $this->render('userslp/profilePsycho.html.twig', array(
            'userslp' => $userSlp,
            'formPsycho' => $formPsycho,
            'questions' => $questions
      ));

    }


    /**
     * Finds and displays a userSlp entity.
     *
     * @Route("/{id}", name="userslp_show")
     * @Method("GET")
     * @param UserSlp $userSlp
     * @return Response
     */
    public function showAction(UserSlp $userSlp)
    {
        $deleteForm = $this->createDeleteForm($userSlp);
        $answerRepo = $this->getDoctrine()->getRepository(Answer::class);
        $userSlpRepo = $this->getDoctrine()->getRepository(UserSlp::class);

        $connectedUser = $userSlpRepo->findOneByGaeaUserId($this->getUser()->getId());

        if ($connectedUser->getRoleSlp() == 1 || $connectedUser->getRoleSlp() == 2) {
            $users = $userSlpRepo->findAll();
        }else{
            $users = false;
        }

        $answers = $answerRepo->findBy(["userSlp" => $userSlp], ["question" => "ASC"]);

        return $this->render('userslp/show.html.twig', array(
            'answers' => $answers,
            'users' => $users,
            'userSlp' => $userSlp,
            'connectedUser' => $connectedUser,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing userSlp entity.
     *
     * @Route("/{id}/edit", name="userslp_edit")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @param UserSlp $userSlp
     * @return RedirectResponse|Response
     */
    public function editAction(Request $request, UserSlp $userSlp)
    {
        $deleteForm = $this->createDeleteForm($userSlp);
        $editForm = $this->createForm('AppBundle\Form\UserSlpType', $userSlp);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('userslp_edit', array('id' => $userSlp->getId()));
        }

        return $this->render('userslp/edit.html.twig', array(
            'userSlp' => $userSlp,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a userSlp entity.
     *
     * @Route("/{id}", name="userslp_delete")
     * @Method("DELETE")
     * @param Request $request
     * @param UserSlp $userSlp
     * @return RedirectResponse
     */
    public function deleteAction(Request $request, UserSlp $userSlp)
    {
        $form = $this->createDeleteForm($userSlp);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($userSlp);
            $em->flush();
        }

        return $this->redirectToRoute('userslp_index');
    }

    /**
     * Creates a form to delete a userSlp entity.
     *
     * @param UserSlp $userSlp The userSlp entity
     *
     * @return FormInterface
     */
    private function createDeleteForm(UserSlp $userSlp)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('userslp_delete', array('id' => $userSlp->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }



    /**
     * permet de reseter la progression de l utilisateur sur le questionnaire profil (conserve ses réponses) pour tester
     *
     * @Route("/reset/eco_profile", name="userslp_reset_eco_profile")
     * @Method({"GET", "POST"})
     *
     */
    public function resetEcoProfile()
    {
        $userSlpRepo = $this->getDoctrine()->getRepository(UserSlp::class);
        $surveyProgressRepo = $this->getDoctrine()->getRepository(SurveyProgress::class);
        $surveyRepo = $this->getDoctrine()->getRepository(Survey::class);

        $currentUserSlp = $userSlpRepo->findOneByGaeaUserId($this->getUser()->getId());
        $profileSurvey = $surveyRepo->findOneById("1");

        /* on vois si le questionnaire profil a été fait par l utilisateur connecté */
        $surveyProgress = $surveyProgressRepo->findOneBy(["user" => $currentUserSlp, "survey" => $profileSurvey]);

        if (isset($surveyProgress)) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($surveyProgress);
            $em->flush();
        }

        return $this->redirectToRoute('userslp_portail');

    }

}
