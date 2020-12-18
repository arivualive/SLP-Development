<?php

namespace GaeaUserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use AppBundle\Entity\UserSlp;
use AppBundle\Entity\SurveyProgress;
use QuestionBundle\Entity\Survey;
use QuestionBundle\Entity\Answer;
use QuestionBundle\Entity\Answer_choice;

use AppBundle\Repository\UserSlpRepository;
use AppBundle\Repository\SurveyProgressRepository;
use QuestionBundle\Repository\SurveyRepository;

class SecurityController extends Controller
{
    /**
     * @Route("/redirectLogin")
     */
    public function redirectLoginAction()
    {
        $userSlpRepo = $this->getDoctrine()->getRepository(UserSlp::class);
        $surveyRepo = $this->getDoctrine()->getRepository(Survey::class);
        $surveyProgressRepo = $this->getDoctrine()->getRepository(SurveyProgress::class);
        $answerRepo = $this->getDoctrine()->getRepository(Answer::class);
        $answerChoiceRepo = $this->getDoctrine()->getRepository(Answer_choice::class);
        
        $form = 1;
        $done = 0;
        $lastQuestionNumber = 0;

        if($this->getUser() != null) {
            $currentUserSlp = $userSlpRepo->findOneByGaeaUserId($this->getUser()->getId());
            $profileSurvey = $surveyRepo->findOneById("1");
            if ($currentUserSlp) {
                $userSlp = $currentUserSlp;

                //récupération du suivi du questionnaire Socio-éco de l'utilisateur
                $surveyProgress = $surveyProgressRepo->findOneBy(["user" => $currentUserSlp, "survey" => $profileSurvey]);

                if (isset($surveyProgress)) {
//                    dump($surveyProgress->getLastAnwseredQuestion());
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
                        $answers = $answerRepo->findByUserSlp($currentUserSlp, $profileSurvey);
                        $lastquestionAnswer = $answers[sizeof($answers)-1]->getQuestion();
                        $answerChoice = $answerChoiceRepo->getSubQ($currentUserSlp, $lastquestionAnswer);
                    }
                }
            }
        }
        return $this->redirectToRoute('userslp_portail');
    }

}
