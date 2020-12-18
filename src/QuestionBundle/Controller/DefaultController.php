<?php

namespace QuestionBundle\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\UserSlp;
use QuestionBundle\Entity\Question;
use QuestionBundle\Entity\Sub_question;
use QuestionBundle\Entity\Answer;
use QuestionBundle\Repository\QuestionRepository;
use QuestionBundle\Repository\AnswerRepository;
use QuestionBundle\Entity\Answer_choice;
use QuestionBundle\Repository\ChoiceRepository;
use QuestionBundle\Entity\Choice;
use AppBundle\Repository\UserSlpRepository;
use QuestionBundle\Entity\Answer_free;
use QuestionBundle\Entity\Answer_scale;
use AppBundle\Entity\SurveyProgress;
use QuestionBundle\Entity\Manger;
use QuestionBundle\Entity\Reponse_thematique;
use AppBundle\Entity\ProgressManger;

class DefaultController extends Controller

{

     /**
     * @Route("/Reponse/thematique", name="thematique_reponse")
     * @Method({"GET", "POST"})
     */

    public function reponseThematique(Request $request)
    
    {
            //instance des repository
            $userSlpRepo = $this->getDoctrine()->getRepository(UserSlp::class);
            $reponseThematiqueRepo = $this->getDoctrine()->getRepository(Reponse_thematique::class);
            $questionMangerRepo = $this->getDoctrine()->getRepository(Manger::class);
            $ProgressMangerRepo = $this->getDoctrine()->getRepository(ProgressManger::class);


            $em = $this->getDoctrine()->getManager();
            $userSlp = $userSlpRepo->findOneByGaeaUserId($this->getUser()->getId());
            $datas = $request->request->all();
            $QuestionEncours='';
           
           foreach ($datas as $data => $value ){
            $question = $questionMangerRepo->find($data);  

           //supprime la reponse existante pour la remplacer par une nouvelle reponse de l'utilisateur     
            $reponsexistante = $reponseThematiqueRepo->findOneBy(["userSlp" => $userSlp, "manger" => $question]);
            if (isset($reponsexistante) && ($question != $QuestionEncours)) {
                $em->remove($reponsexistante);
                $em->flush();
            }
            //sinon on enregsitre la reponse pour un nouveau utilisateur
            $answer = new Reponse_thematique;
            $answer->setValue($value);
            
            //on trouve la progression du sondage de notre user (ex:l'user s'est arreter a la question 20 il reprendra a la 20)
            $ProgressManger = $ProgressMangerRepo->findOneBy((["user" => $userSlp, "survey" => $question->getSurvey()]));

            $lastAnsweredQuestion = $question;
             if (isset($ProgressManger)) {
                $ProgressManger->setLastAnwseredQuestion($lastAnsweredQuestion);
            }else{
                $ProgressManger = new ProgressManger;
                $ProgressManger->setLastAnwseredQuestion($lastAnsweredQuestion);
                $ProgressManger->setUser($userSlp);
                $ProgressManger->setSurvey($lastAnsweredQuestion->getSurvey());
                $ProgressManger->setDone(0);
                
                $em->persist($ProgressManger);
                $em->flush();
            }          


            $surveyLastQuestion = $questionMangerRepo->findLastQuestionBySurvey($lastAnsweredQuestion->getSurvey())[0];
           
            //si la question qui vient d etre repondue est la derniere du questionnaire , on passe le parametre "done" a true (1)
             if ($surveyLastQuestion->getID() == $lastAnsweredQuestion->getID() ) {
                $ProgressManger->setDone(1);
            }

            $answer->setManger($question);             
            $answer->setUserSlp($userSlp);          
            $em->persist($answer); 
            $em->flush();

            
            
           }           
        return new response('ok');
    }



    /**
     * @Route("/answer/save", name="answer_save")
     * @Method({"GET", "POST"})
     */
    public function saveAnswer(Request $request)
    {
        /* Repository */
        $questionRepo =  $this->getDoctrine()->getRepository(Question::class);
        $answerRepo =  $this->getDoctrine()->getRepository(Answer::class);
        $choiceRepo = $this->getDoctrine()->getRepository(Choice::class);
        $userSlpRepo = $this->getDoctrine()->getRepository(UserSlp::class);
        $surveyProgressRepo = $this->getDoctrine()->getRepository(SurveyProgress::class);
        
        /* Entity Manager */
        $em = $this->getDoctrine()->getManager();
        
        /* quelques variable dont on as besoin */

        $datas = $request->request->all();
        $currentQuestion = "";
        $userSlp = $userSlpRepo->findOneByGaeaUserId($this->getUser()->getId());
        
        
        foreach ($datas as $data => $value) {
            $questionId = explode("_", $data);
            $question = $questionRepo->findOneById($questionId[0]);

            $previousAnswer = $answerRepo->findOneBy(["userSlp" => $userSlp, "question" => $question]);

            if (isset($previousAnswer) && ($question != $currentQuestion)) {
                $em->remove($previousAnswer);
                $em->flush();
            }
            
            switch ($question) {
               case 'Question_choice':
                    $choice = $choiceRepo->findOneById($value);

                    if ($question->getIsUnique()) {

                        $answer = new Answer_choice;
                        
                    }else{

                        if ($question != $currentQuestion) {
                            $answer = new Answer_choice;
                        }else {
                            $answer = $previousAnswer;
                        }

                    }

                    $answer->setChoices($choice);

                    break;
                
                case 'Question_free':
                    
                    $answer = new Answer_free;
                    $answer->setFreeAswere($value);
                    
                    break;
                
                case 'Question_scale':
                    
                    $answer = new Answer_scale;
                    $answer->setScale($value);

                    break;

                case 'Conditional_question_choice':

                    
                    $choice = $choiceRepo->findOneById($value);
                    if ($question->getIsUnique()) {

                        $answer = new Answer_choice;
                    }else{

                        if ($question != $currentQuestion) {
                            $answer = new Answer_choice;
                        }else {
                            $answer = $previousAnswer;
                        }

                    }
                    
                    $answer->setChoices($choice);
                    break;
                
                case 'Conditional_question_free':
                    
                    $answer = new Answer_free;
                    $answer->setFreeAswere($value);

                    break;
                
                case 'Sub_question_choice':

                    $choice = $choiceRepo->findOneById($value);

                    if ($question->getIsUnique()) {

                        $answer = new Answer_choice;
                        
                    }else{

                        if ($question != $currentQuestion) {
                            $answer = new Answer_choice;
                        }else {
                            $answer = $answerRepo->findOneBy(["userSlp" => $userSlp, "question" => $question]);
                        }

                    }

                    $answer->setChoices($choice);
                    
                    break;
                
                case 'Sub_question_free':

                    $answer = new Answer_free;
                    $answer->setFreeAswere($value);

                    break;
                
                case 'Sub_question_scale':
                    
                    $answer = new Answer_scale;
                    
                    $answer->setScale($value);

                    break; 
                
                default:
                    /* ne doit pas arriver */ 
                    break;
            }

            //on trouve le survey progress de notre user pour ce survey
            $surveyProgress = $surveyProgressRepo->findOneBy((["user" => $userSlp, "survey" => $question->getSurvey()]));
            
            // si la derniere question repondue est une sous question, alors on enregistre ca "master question" comme lastAnsweredQuestion
            if ($question instanceof Sub_question) {
                $lastAnsweredQuestion = $questionRepo->findOneById($question->getMasterQuestion()->getId());
            }else{
                $lastAnsweredQuestion = $question;
            }

            // si le survey progress existe , on modifie juste la lastAnsweredQuestion sinon on le crée.
            if (isset($surveyProgress)) {
                $surveyProgress->setLastAnwseredQuestion($lastAnsweredQuestion);
            }else{
                $surveyProgress = new SurveyProgress;
                $surveyProgress->setLastAnwseredQuestion($lastAnsweredQuestion);
                $surveyProgress->setUser($userSlp);
                $surveyProgress->setSurvey($lastAnsweredQuestion->getSurvey());
                $surveyProgress->setDone(0);
                
                $em->persist($surveyProgress);
                $em->flush();
            }
            
            // on interoge le repo pour prendre la derniere question du questionnaire, la reponse est sous forme de tableau , donc on prend l objet a l indice 0
            $surveyLastQuestion = $questionRepo->findLastQuestionBySurvey($lastAnsweredQuestion->getSurvey())[0];
            dump($surveyLastQuestion);
            //si la question qui vient d etre repondue est la derniere du questionnaire , on passe le parametre "done" a true (1)






//             if ($surveyLastQuestion->getID() == $lastAnsweredQuestion->getID()) {
//                $surveyProgress->setDone(1);
//            }






            $answer->setQuestion($question);
            $answer->setUserSlp($userSlp);
            $em->persist($answer);
            $em->flush();

            /* on enregistre la question en cour pour savoir si la reponse doit etre modifié , ou si il sagit d un choix multiple */
            $currentQuestion = $question;

        }
 
        exit;
    }
}