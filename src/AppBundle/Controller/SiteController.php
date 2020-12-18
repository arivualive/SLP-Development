<?php

namespace AppBundle\Controller;

use AppBundle\AppBundle;
use AppBundle\Entity\Categorie_favoris;
use AppBundle\Entity\Favoris;
use GaeaUserBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\Repository\ProgressMangerRepository;
use AppBundle\Repository\UserSlpRepository;

use QuestionBundle\Repository\QuestionRepository;
use QuestionBundle\Repository\MangerRepository;
use QuestionBundle\Repository\AnswerRepository;
use QuestionBundle\Repository\ChoiceRepository;

use AppBundle\Entity\UserSlp;
use AppBundle\Entity\SurveyProgress;
use AppBundle\Entity\ProgressManger;
use AppBundle\Form\ContactType;


use AppBundle\Entity\Categories;
use AppBundle\Entity\SousCategories;
use AppBundle\Entity\Cost;
use QuestionBundle\Entity\Question;
use QuestionBundle\Entity\Sub_question;
use QuestionBundle\Entity\Answer;
use QuestionBundle\Entity\Answer_choice;
use QuestionBundle\Entity\Choice;
use AppBundle\Form\PersonSpendingType;
use QuestionBundle\Entity\Answer_free;
use QuestionBundle\Entity\Answer_scale;
use QuestionBundle\Entity\Conditional_question;
use QuestionBundle\Entity\Question_master;
use QuestionBundle\Entity\Conditional_question_master;
use QuestionBundle\Entity\Survey;
use QuestionBundle\Entity\Manger;
use QuestionBundle\Entity\Reponse_thematique;
use AppBundle\Entity\Produit;
use AppBundle\Entity\Depenses;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use AppBundle\Entity\Period;
use AppBundle\Entity\Currency;
use AppBundle\Entity\Quantity;
use AppBundle\Entity\Brand;

use AppBundle\Service\UserSlpGaeaUser;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class SiteController extends Controller
{
    /**
     * @Route("/", name="site_index")
     */
    public function indexAction()
    {
        return $this->render('site/site_index.html.twig');
    }


    /**
     * @Route("/mentionslegales", name="site_mentions")
     */
    public function mentionAction()
    {
        return $this->render('site/mentions.html.twig');
    }


    /**
     * @Route("/manger", name="site_manger")
     */
    public function mangerAction()
    {

        $userSlpRepo = $this->getDoctrine()->getRepository(UserSlp::class);
        $surveyRepo = $this->getDoctrine()->getRepository(Survey::class);
        $questionMangerRepo = $this->getDoctrine()->getRepository(Manger::class);

        if ($this->getUser() != null) {
            $UserConecte = $userSlpRepo->findOneByGaeaUserId($this->getUser()->getId());
            $Sondage = $surveyRepo->findOneById("2");
            $done = 0;
            $DerniereQuestion = 0;
            $mangers = $questionMangerRepo->findQuestionBySurvey($Sondage, $DerniereQuestion);
            if ($UserConecte == null) {
                $userSlp = null;
                $mangers = null;
                $form = null;
            } else {
                $userSlp = $UserConecte;
                if ($done) {
                    $mangers = [];
                    $form = 0;

                } else {

                    $mangers = $questionMangerRepo->findQuestionBySurvey($Sondage, $DerniereQuestion);
                    $form = 1;
                }
            }
        } else {
            $userSlp = null;
            $form = null;
            $mangers = null;
        }
        return $this->render('site/site_manger.html.twig', array(
            'userslp' => $userSlp,
            'form' => $form,
            'manger' => $mangers
        ));

    }

    /**
     * @Route("/mangerTest", name="site_mangerTest")
     */
    public function mangerTest()
    {
        $userSlpRepo = $this->getDoctrine()->getRepository(UserSlp::class);
        $surveyProgressRepo = $this->getDoctrine()->getRepository(SurveyProgress::class);
        $questionRepo = $this->getDoctrine()->getRepository(Question::class);
        $surveyRepo = $this->getDoctrine()->getRepository(Survey::class);
        $answerRepo = $this->getDoctrine()->getRepository(Answer::class);
        $answerChoiceRepo = $this->getDoctrine()->getRepository(Answer_choice::class);

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
            $profileSurvey = $surveyRepo->findOneById("2");
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
                        $answers = $answerRepo->findByUserSlp($currentUserSlp, 2);
                        $lastquestionAnswer = $answers[sizeof($answers)-1]->getQuestion();
                        $answerChoice = $answerChoiceRepo->getSubQ($currentUserSlp, $lastquestionAnswer);
                        if($lastQuestionNumber==28){
                            if($answerChoice->getChoices()->getValues()[0]->getName()=="Omnivore"){
                                $done = true;
                                $form = 0;
                                $questions = [];
                            }
                        }else{
                            $questions = $questionRepo->findQuestionWithoutSubBySurvey($profileSurvey);
                            $form = 1;
                        }
                    }
                } else {
                    $questions = $questionRepo->findQuestionWithoutSubBySurvey($profileSurvey);
                }
            }
        }
//        dump($questions[0]->getTheme()->getSoustheme());
        dump($answers);
//        dump($questions[1]->getTheme()->getSoustheme());

        return $this->render('site/site_manger_test.html.twig', array(
            'userslp' => $userSlp,
            'form' => $form,
            'questionsManger' => $questions,
            'done' => $done,
            'lastquestionnumber' => $lastQuestionNumber,
            'numberOfQuestion' => $numberOfQuestion,
            'answers' => $answers
        ));
    }


    /**
     * @Route("/a propos", name="site_about")
     */
    public function aboutAction()
    {
        return $this->render('site/site_about.html.twig');
    }


    /**
     * @Route("/contact", name="contact")
     * @Method({"GET", "POST"})
     */
    public function contactAction(Request $request)
    {

        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $name = $form->get("name")->getData();
            $mail = $form->get("email")->getData();
            $content = $form->get("message")->getData();

            $message = \Swift_Message::newInstance()
                ->setSubject("Message envoyé depuis le site")
                ->setFrom($mail)
                ->setTo("monadresse@local.local")
                ->setBody($this->renderView('site/mail/contact.txt.twig', [
                    'mail' => $mail,
                    "name" => $name,
                    "message" => $content
                ]));

            $this->get('mailer')->send($message);

            $this->get('session')->getFlashBag()->add('success', 'Votre message a bien été envoyé');

            return $this->redirect($this->generateUrl('site_contact'));
        }

        return $this->render('site/site_contact.html.twig', [
            "formulaire" => $form->createView()
        ]);
    }

    /**
     * @Route("/ateliereco", name="atelier_eco")
     *
     */
    public function atelierEco()
    {
        return $this->render('site/site_ateliereco.html.twig');
    }

    /**
     * @Route("/ecopanier", name="eco_panier")
     *
     */
    public function ecoPanier()
    {
        $form = null;
        $userSlp = null;
        $user = $this->getUser();
        if ($user == null) {
            $form == null;
        } else {
            $userSlpRepo = $this->getDoctrine()->getRepository(UserSlp::class);
            $ProgressMangerRepo = $this->getDoctrine()->getRepository(ProgressManger::class);
            $surveyRepo = $this->getDoctrine()->getRepository(Survey::class);
            $questionMangerRepo = $this->getDoctrine()->getRepository(Manger::class);
            $UserConecte = $userSlpRepo->findOneByGaeaUserId($this->getUser()->getId());
            $Sondage = $surveyRepo->findOneById("2");
            $ProgressManger = $ProgressMangerRepo->findOneBy(["user" => $UserConecte, "survey" => $Sondage]);

            if (isset($ProgressManger)) {
                $done = $ProgressManger->getDone();
                $DerniereQuestion = $ProgressManger->getLastAnwseredQuestion()->getNumquestion();

            } else {
                $done = 0;
                $DerniereQuestion = 0;
            }
            $mangers = $questionMangerRepo->findQuestionBySurvey($Sondage, $DerniereQuestion);
            if ($UserConecte == null) {
                $userSlp = null;
                $mangers = null;
                $form = null;
            } else {
                $userSlp = $UserConecte;
                if ($done) {
                    $mangers = [];
                    $form = 0;

                } else {

                    $mangers = $questionMangerRepo->findQuestionBySurvey($Sondage, $DerniereQuestion);
                    $form = 1;
                }
            }
        }
        return $this->render('site/site_ecopaniers.html.twig', [
            "form" => $form,
            "userslp" => $userSlp
        ]);
    }

    /**
     * @Route("/atelierportail", name="atelier_portail")
     *
     */
    public function atelierPortail()
    {
        /*if ($user == null) {
            $form == null;
        } */
        $form = null;
        $userSlp = null;
        $user = $this->getUser();
        if ($user == null) {
            $form == null;
        } else {
            $userSlpRepo = $this->getDoctrine()->getRepository(UserSlp::class);
            $ProgressMangerRepo = $this->getDoctrine()->getRepository(ProgressManger::class);
            $surveyRepo = $this->getDoctrine()->getRepository(Survey::class);
            $questionMangerRepo = $this->getDoctrine()->getRepository(Manger::class);
            $UserConecte = $userSlpRepo->findOneByGaeaUserId($this->getUser()->getId());
            $Sondage = $surveyRepo->findOneById("2");
            $ProgressManger = $ProgressMangerRepo->findOneBy(["user" => $UserConecte, "survey" => $Sondage]);

            if (isset($ProgressManger)) {
                $done = $ProgressManger->getDone();
                $DerniereQuestion = $ProgressManger->getLastAnwseredQuestion()->getNumquestion();

            } else {
                $done = 0;
                $DerniereQuestion = 0;
            }
            $mangers = $questionMangerRepo->findQuestionBySurvey($Sondage, $DerniereQuestion);
            if ($UserConecte == null) {
                $userSlp = null;
                $mangers = null;
                $form = null;
            } else {
                $userSlp = $UserConecte;
                if ($done) {
                    $mangers = [];
                    $form = 0;

                } else {

                    $mangers = $questionMangerRepo->findQuestionBySurvey($Sondage, $DerniereQuestion);
                    $form = 1;
                }
            }
        }
        return $this->render('site/site_atelierportail.html.twig', [
            "form" => $form,
            "userslp" => $userSlp
        ]);
    }

    /**
     * @Route("/atelieralimentation", name="atelier_alimentation")
     *
     */
    public function atelierAlimentation()
    {
        $form = null;
        $userSlp = null;
        $user = $this->getUser();
        if ($user == null) {
            $form == null;
        } else {
            $userSlpRepo = $this->getDoctrine()->getRepository(UserSlp::class);
            $ProgressMangerRepo = $this->getDoctrine()->getRepository(ProgressManger::class);
            $surveyRepo = $this->getDoctrine()->getRepository(Survey::class);
            $questionMangerRepo = $this->getDoctrine()->getRepository(Manger::class);
            $UserConecte = $userSlpRepo->findOneByGaeaUserId($this->getUser()->getId());
            $Sondage = $surveyRepo->findOneById("2");
            $ProgressManger = $ProgressMangerRepo->findOneBy(["user" => $UserConecte, "survey" => $Sondage]);

            if (isset($ProgressManger)) {
                $done = $ProgressManger->getDone();
                $DerniereQuestion = $ProgressManger->getLastAnwseredQuestion()->getNumquestion();

            } else {
                $done = 0;
                $DerniereQuestion = 0;
            }
            $mangers = $questionMangerRepo->findQuestionBySurvey($Sondage, $DerniereQuestion);
            if ($UserConecte == null) {
                $userSlp = null;
                $mangers = null;
                $form = null;
            } else {
                $userSlp = $UserConecte;
                if ($done) {
                    $mangers = [];
                    $form = 0;

                } else {

                    $mangers = $questionMangerRepo->findQuestionBySurvey($Sondage, $DerniereQuestion);
                    $form = 1;
                }
            }
        }
        return $this->render('site/site_atelieralimentation.html.twig', [
            "form" => $form,
            "userslp" => $userSlp
        ]);
    }

    /**
     * @Route("/ateliermode", name="atelier_mode")
     *
     */
    public function atelierMode()
    {
        $form = null;
        $userSlp = null;
        $user = $this->getUser();
        if ($user == null) {
            $form == null;
        } else {
            $userSlpRepo = $this->getDoctrine()->getRepository(UserSlp::class);
            $ProgressMangerRepo = $this->getDoctrine()->getRepository(ProgressManger::class);
            $surveyRepo = $this->getDoctrine()->getRepository(Survey::class);
            $questionMangerRepo = $this->getDoctrine()->getRepository(Manger::class);
            $UserConecte = $userSlpRepo->findOneByGaeaUserId($this->getUser()->getId());
            $Sondage = $surveyRepo->findOneById("2");
            $ProgressManger = $ProgressMangerRepo->findOneBy(["user" => $UserConecte, "survey" => $Sondage]);

            if (isset($ProgressManger)) {
                $done = $ProgressManger->getDone();
                $DerniereQuestion = $ProgressManger->getLastAnwseredQuestion()->getNumquestion();

            } else {
                $done = 0;
                $DerniereQuestion = 0;
            }
            $mangers = $questionMangerRepo->findQuestionBySurvey($Sondage, $DerniereQuestion);
            if ($UserConecte == null) {
                $userSlp = null;
                $mangers = null;
                $form = null;
            } else {
                $userSlp = $UserConecte;
                if ($done) {
                    $mangers = [];
                    $form = 0;

                } else {

                    $mangers = $questionMangerRepo->findQuestionBySurvey($Sondage, $DerniereQuestion);
                    $form = 1;
                }
            }
        }
        return $this->render('site/site_ateliermode.html.twig', [
            "form" => $form,
            "userslp" => $userSlp
        ]);
    }

    /**
     * @Route("/ateliermaquillage", name="atelier_maquillage")
     *
     */
    public function atelierMaquillage()
    {
        $form = null;
        $userSlp = null;
        $user = $this->getUser();
        if ($user == null) {
            $form == null;
        } else {
            $userSlpRepo = $this->getDoctrine()->getRepository(UserSlp::class);
            $ProgressMangerRepo = $this->getDoctrine()->getRepository(ProgressManger::class);
            $surveyRepo = $this->getDoctrine()->getRepository(Survey::class);
            $questionMangerRepo = $this->getDoctrine()->getRepository(Manger::class);
            $UserConecte = $userSlpRepo->findOneByGaeaUserId($this->getUser()->getId());
            $Sondage = $surveyRepo->findOneById("2");
            $ProgressManger = $ProgressMangerRepo->findOneBy(["user" => $UserConecte, "survey" => $Sondage]);

            if (isset($ProgressManger)) {
                $done = $ProgressManger->getDone();
                $DerniereQuestion = $ProgressManger->getLastAnwseredQuestion()->getNumquestion();

            } else {
                $done = 0;
                $DerniereQuestion = 0;
            }
            $mangers = $questionMangerRepo->findQuestionBySurvey($Sondage, $DerniereQuestion);
            if ($UserConecte == null) {
                $userSlp = null;
                $mangers = null;
                $form = null;
            } else {
                $userSlp = $UserConecte;
                if ($done) {
                    $mangers = [];
                    $form = 0;

                } else {

                    $mangers = $questionMangerRepo->findQuestionBySurvey($Sondage, $DerniereQuestion);
                    $form = 1;
                }
            }
        }
        return $this->render('site/site_ateliermaquillage.html.twig', [
            "form" => $form,
            "userslp" => $userSlp
        ]);
    }

    /**
     * @Route("/ateliersante", name="atelier_sante")
     *
     */
    public function atelierSante()
    {
        $form = null;
        $userSlp = null;
        $user = $this->getUser();
        if ($user == null) {
            $form == null;
        } else {
            $userSlpRepo = $this->getDoctrine()->getRepository(UserSlp::class);
            $ProgressMangerRepo = $this->getDoctrine()->getRepository(ProgressManger::class);
            $surveyRepo = $this->getDoctrine()->getRepository(Survey::class);
            $questionMangerRepo = $this->getDoctrine()->getRepository(Manger::class);
            $UserConecte = $userSlpRepo->findOneByGaeaUserId($this->getUser()->getId());
            $Sondage = $surveyRepo->findOneById("2");
            $ProgressManger = $ProgressMangerRepo->findOneBy(["user" => $UserConecte, "survey" => $Sondage]);

            if (isset($ProgressManger)) {
                $done = $ProgressManger->getDone();
                $DerniereQuestion = $ProgressManger->getLastAnwseredQuestion()->getNumquestion();

            } else {
                $done = 0;
                $DerniereQuestion = 0;
            }
            $mangers = $questionMangerRepo->findQuestionBySurvey($Sondage, $DerniereQuestion);
            if ($UserConecte == null) {
                $userSlp = null;
                $mangers = null;
                $form = null;
            } else {
                $userSlp = $UserConecte;
                if ($done) {
                    $mangers = [];
                    $form = 0;

                } else {

                    $mangers = $questionMangerRepo->findQuestionBySurvey($Sondage, $DerniereQuestion);
                    $form = 1;
                }
            }
        }
        return $this->render('site/site_ateliersante.html.twig', [
            "form" => $form,
            "userslp" => $userSlp
        ]);
    }

    /**
     * @Route("/atelierchoisirconsommer", name="atelier_choisirconsommer")
     *
     */
    public function atelierChoisirConsommer()
    {
        $form = null;
        $userSlp = null;
        $user = $this->getUser();
        if ($user == null) {
            $form == null;
        } else {
            $userSlpRepo = $this->getDoctrine()->getRepository(UserSlp::class);
            $ProgressMangerRepo = $this->getDoctrine()->getRepository(ProgressManger::class);
            $surveyRepo = $this->getDoctrine()->getRepository(Survey::class);
            $questionMangerRepo = $this->getDoctrine()->getRepository(Manger::class);
            $UserConecte = $userSlpRepo->findOneByGaeaUserId($this->getUser()->getId());
            $Sondage = $surveyRepo->findOneById("2");
            $ProgressManger = $ProgressMangerRepo->findOneBy(["user" => $UserConecte, "survey" => $Sondage]);

            if (isset($ProgressManger)) {
                $done = $ProgressManger->getDone();
                $DerniereQuestion = $ProgressManger->getLastAnwseredQuestion()->getNumquestion();

            } else {
                $done = 0;
                $DerniereQuestion = 0;
            }
            $mangers = $questionMangerRepo->findQuestionBySurvey($Sondage, $DerniereQuestion);
            if ($UserConecte == null) {
                $userSlp = null;
                $mangers = null;
                $form = null;
            } else {
                $userSlp = $UserConecte;
                if ($done) {
                    $mangers = [];
                    $form = 0;

                } else {

                    $mangers = $questionMangerRepo->findQuestionBySurvey($Sondage, $DerniereQuestion);
                    $form = 1;
                }
            }
        }
        return $this->render('site/site_atelierchoisirconsommer.html.twig', [
            "form" => $form,
            "userslp" => $userSlp
        ]);
    }

    /**
     * @Route("/atelierzerowaste", name="atelier_zerowaste")
     *
     */
    public function atelierZeroWaste()
    {
        $form = null;
        $userSlp = null;
        $user = $this->getUser();
        if ($user == null) {
            $form == null;
        } else {
            $userSlpRepo = $this->getDoctrine()->getRepository(UserSlp::class);
            $ProgressMangerRepo = $this->getDoctrine()->getRepository(ProgressManger::class);
            $surveyRepo = $this->getDoctrine()->getRepository(Survey::class);
            $questionMangerRepo = $this->getDoctrine()->getRepository(Manger::class);
            $UserConecte = $userSlpRepo->findOneByGaeaUserId($this->getUser()->getId());
            $Sondage = $surveyRepo->findOneById("2");
            $ProgressManger = $ProgressMangerRepo->findOneBy(["user" => $UserConecte, "survey" => $Sondage]);

            if (isset($ProgressManger)) {
                $done = $ProgressManger->getDone();
                $DerniereQuestion = $ProgressManger->getLastAnwseredQuestion()->getNumquestion();

            } else {
                $done = 0;
                $DerniereQuestion = 0;
            }
            $mangers = $questionMangerRepo->findQuestionBySurvey($Sondage, $DerniereQuestion);
            if ($UserConecte == null) {
                $userSlp = null;
                $mangers = null;
                $form = null;
            } else {
                $userSlp = $UserConecte;
                if ($done) {
                    $mangers = [];
                    $form = 0;

                } else {

                    $mangers = $questionMangerRepo->findQuestionBySurvey($Sondage, $DerniereQuestion);
                    $form = 1;
                }
            }
        }
        return $this->render('site/site_atelierzerowaste.html.twig', [
            "form" => $form,
            "userslp" => $userSlp
        ]);
    }

    /**
     * @Route("/devenezmembre", name="devenez_membre")
     *
     */
    public function devenezMembreAction()
    {
        return $this->render('site/devenez_membre.html.twig');
    }

    /**
     * @Route("/empreintechromatique", name="empreinte_chromatique")
     *
     */
    public function empreinteAction()
    {
        return $this->render('site/empreinte_chromatique.html.twig');
    }

    /**
     * @Route("/empreintechromatique-individu", name="empreinte_chromatique_individu")
     *
     */
    public function empreinteActionIndividu()
    {
        return $this->render('site/empreinte_chromatique_individu.html.twig');
    }

    /**
     * @Route("/empreintechromatique-famille", name="empreinte_chromatique_famille")
     *
     */
    public function empreinteActionFamille()
    {
        return $this->render('site/empreinte_chromatique_famille.html.twig');
    }

    /**
     * @Route("/actualites/menu", name="actualites_menu")
     *
     */
    public function actuMenu()
    {
        /*$em = $this->getDoctrine()->getManager();
        $sections = $em->getRepository('AppBundle:ActualitySection')->findAll();
        $actualities = $em->getRepository('AppBundle:Actuality')->findAll();*/
        return $this->render('site/actualites_menu.html.twig' /*, ['actualities' => $actualities,
        'sections' => $sections]*/
        );
    }

    /**
     * @Route("/actualites/type", name="actualite_type")
     *
     */
    public function actuType()
    {
        return $this->render('site/actualite_type.html.twig');
    }

    /**
     * @Route("/actualitetype/1", name="actualite_type1")
     *
     */
    public function actuType1()
    {
        return $this->render('site/actualite_type1.html.twig');
    }

    /**
     * @Route("/actualitetype/2", name="actualite_type2")
     *
     */
    public function actuType2()
    {
        return $this->render('site/actualite_type2.html.twig');
    }

    /**
     * @Route("/actualitetype/3", name="actualite_type3")
     *
     */
    public function actuType3()
    {
        return $this->render('site/actualite_type3.html.twig');
    }


    /**
     * @Route("/actualitetype/4", name="actualite_type4")
     *
     */
    public function actuType4()
    {
        return $this->render('site/actualite_type4.html.twig');
    }

    /**
     * @Route("/actualitetype/5", name="actualite_type5")
     *
     */
    public function actuType5()
    {
        return $this->render('site/actualite_type5.html.twig');
    }

    /**
     * @Route("/actualitetype/6", name="actualite_type6")
     *
     */
    public function actuType6()
    {
        return $this->render('site/actualite_type6.html.twig');
    }

    /**
     * @Route("/actualitetype/7", name="actualite_type7")
     *
     */
    public function actuType7()
    {
        return $this->render('site/actualite_type7.html.twig');
    }

    /**
     * @Route("/community", name="community")
     *
     */
    public function community()
    {
        return $this->render('site/community.html.twig');
    }

/**
     * @Route("/join_community", name="join_community")
     * @Method({"GET", "POST"})
     *
     */
    public function joinCommunity(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $userSlpRepo = $this->getDoctrine()->getRepository(UserSlp::class);

        $data = json_decode($request->request->get('myData'));

        $joined = false;
        if($data =="oui"){
            $joined = true;
        }

        $UserConecte = $userSlpRepo->findOneByGaeaUserId($this->getUser()->getId());
        $UserConecte->setSlc($joined);
        $em->persist($UserConecte);
        $em->flush();
        return new Response();
    }
    /**
     * @Route("/humans", name="humans")
     *
     */
    public function humans()
    {
        return $this->render('site/humans.html.twig');
    }

    /**
     * @Route("/humans/article", name="humans_article")
     *
     */
    public function humansArticle()
    {
        return $this->render('site/humans_article.html.twig');
    }

    /**
     * @Route("/ambassadeur", name="ambassadeur")
     *
     */
    public function ambassadeur()
    {
        return $this->render('site/ambassadeur.html.twig');
    }

    /**
     * @Route("/template-ecoprofil", name="test_portail")
     */
    public function testQuestion()
    {
        $questionsRepo = $this->getDoctrine()->getRepository(Question::class);

        $questions = $questionsRepo->findAll();

        return $this->render('site/test_portail.html.twig', array(
            'questions' => $questions,
        ));
    }

    /**
     * @Route("/communication-profile", name="communication-profile")
     */
    public function communicationProfile()
    {
        return $this->render('site/profil/communication-profile.html.twig');
        
    }

    /**
     * @Route("/living-profile", name="living-profile")
     */
    public function livingProfile()
    {
        return $this->render('site/profil/living-profile.html.twig');
        
    }

    /**
     * @Route("/eating-profile", name="eating-profile", name="test_Dashboard")")
     */
    public function eatingProfile()
    {
        return $this->render('site/profil/eating-profile.html.twig');
        
    }

    /**
     * @Route("/take-care-of-yourself-profile", name="take-care-of-yourself")
     */
    public function takeCareOfYourselfProfile()
    {
        return $this->render('site/profil/take-care-of-yourself-profile.html.twig');
    }

    /**
     * @Route("/moving-profile", name="moving-profile")
     */
    public function movingProfile()
    {
        return $this->render('site/profil/moving-profile.html.twig');
    }

    /**
     * @Route("/relaxing-profile", name="relaxing-profile")
     */
    public function relaxingProfile()
    {
        return $this->render('site/profil/relaxing-profile.html.twig');
    }

    /**
     * @Route("/dressing-profile", name="dressing-profile")
     */
    public function dressingProfile()
    {
        return $this->render('site/profil/dressing-profile.html.twig');
    }

    /**
     * @Route("/working-profile", name="working-profile")
     */
    public function workingProfile()
    {
        return $this->render('site/profil/working-profile.html.twig');
    }

    /**
     * @Route("/results-by-subject", name="results-by-subject")
     */
    public function resultsBySubject()
    {
        return $this->render('site/profil/results-by-subjects.html.twig');
    }

    public function searchUserSlpFromGaeaUser($user) {
        $gaeaUserId = $user->getId();
        $userSlp = $this->getDoctrine()->getRepository('AppBundle:UserSlp')->findOneBy(array('gaeaUserId' => $gaeaUserId));

        return $userSlp;
    }

    

    ///**
    // * @Route("/budgetdepenses", name="budgetdepenses")
    // */
    /*public function budgetdepenses()
    {
        return $this->render('site/programme_budget_depenses.html.twig');
    }*/

    /**
     * @Route("/devenezmembre", name="devenezmembre")
     */
    public function devenezmembre()
    {
        return $this->render('site/devenezmembre.html.twig');

    }

    /**
     * @Route("/test_banniere", name="testbanniere")
     */
    public function testbanniere()
    {
        return $this->render('site/testbanniere.html.twig');

    }

    /**
     * @Route("/gestion_de_projet", name="gestionDeProjet")
     */
    public function gestionProjet()
    {
        return $this->render('site/gestion_projet.html.twig');
    }

    /**
     * @Route("/individual_calendar", name="individual_calendar")
     */
    public function slpIndividualDiary()
    {
        return $this->render('site/agenda/agenda-individuel.html.twig');
    }

    /**
     * @Route("/collective_calendar", name="collective_calendar")
     */
    public function slpCollectiveDiary()
    {
        return $this->render('site/agenda/agenda-collectif.html.twig');
    }

    /**
     * @Route("/favoris", name="favoris")
     */
    public function favoris()
    {
        return $this->render('site/favoris.html.twig');
    }

    /**
     * @Route("/favoris_change", name="favoris_change")
     */
    public function favorisChange()
    {
        return $this->render('site/favoris_change.html.twig');
    }

    /**
     * @Route("/add_favoris", name="add_favoris")
     * @Method,{"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function addfavoris(Request $request)
    {

        $dataJson = $request->request->get('dataJson');

        $jsonDecode = json_decode($dataJson);

        $categorieFavorisRepo = $this->getDoctrine()->getRepository(Categorie_favoris::class);
        $userSlpRepo = $this->getDoctrine()->getRepository(UserSlp::class);
        $userSlp = $userSlpRepo->findOneByGaeaUserId($this->getUser()->getId());

        $em = $this->getDoctrine()->getManager();


        foreach ($jsonDecode as $value) {

            $categorieFavoris = $categorieFavorisRepo->find(
                $value
            );

            /* Si l'id n'est pas vide c'est qu'il y a deja un id en base de donnees donc je sors du foreach
            if(!empty($value)){
                break;
            }
            */
                $userSlp->addCatfavori($categorieFavoris);

                $em->persist($userSlp);
                $em->flush();

        }
        return new Response();
    }





    /**
     * @Route("/test_favoris", name="test_favoris")
     */

    public function testfavoris(){

        $categorieFavorisRepo = $this->getDoctrine()->getRepository(Categorie_favoris::class);
        $userSlpRepo = $this->getDoctrine()->getRepository(UserSlp::class);
        $userSlp = $userSlpRepo->findOneByGaeaUserId($this->getUser()->getId());


        $favoris = $userSlp->getCatfavoris();


        /*
        $favoris = $userSlpRepo->findBy(array(
            'id' => $userSlp
        ));
        */



        return $this->render('site/favoris_change.html.twig', array(
            'favoris' => $favoris,
        ));
    }

    /**
     * @Route("/test_dashboard_personalisation", name="testdashboardpersonalisation")
     */
    public function testdashboardpersonalisation()
    {
        return $this->render('site/test_dashboard_personalisation.html.twig');

    }


}


