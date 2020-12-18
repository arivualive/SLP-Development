<?php
/* QuestionBundle:Notification:list.html.twig */
namespace QuestionBundle\Controller;

use AppBundle\Entity\Feed;
use AppBundle\Entity\UserSlp;
use QuestionBundle\Entity\Answer;
use QuestionBundle\Entity\Choice;
use QuestionBundle\Entity\Question;
use QuestionBundle\Entity\Conditional_question;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class NotificationController extends Controller
{   
    /**
     * @Route("/notificationlist" ,name="notification_list")
     */
    public function notificationAction()
    {        
        $userSlp = $this->getDoctrine()->getRepository(UserSlp::class)->findOneByGaeaUserId($this->getUser()->getId());
        $answerRepository = $this->getDoctrine()->getRepository(Answer::class);
        $questionRepository = $this->getDoctrine()->getRepository(Question::class);
        $conditionalQuestionRepository = $this->getDoctrine()->getRepository(Conditional_question::class);
        $choiceRepository = $this->getDoctrine()->getRepository(Choice::class);

        // trouve la date la plus récente des réponses en parametre l'id du userSlp
        $mostRecentAnswerDate = $answerRepository->findByLatestAnsweredDate($userSlp);

        // trouve les questions  par rapport a la date de réponse
        $newQuestion = $questionRepository->findByLatestQuestiondDate($mostRecentAnswerDate);

        // trouve toutes les dates des réponses qui sont inférieur a la date des questions en paramètre l'id du userSlp
        $questionDateSupAnswerDate = $answerRepository->findByDateQuestionAndAnswer($userSlp);

        if($newQuestion){
            $questionsSocio = $newQuestion;
        }else{
            $questionsSocio = [];
        }
        
        // trouve la question par rapport la date de la derniere réponse  
        foreach( $questionDateSupAnswerDate as $value ) {       
            // ajoute les questions des 2 tableaux $newQuestion et $questionDateSupAnswerDate en enlevant les doublons  
            $oldQuestions[] = $value->getQuestion();   
                
            if(!in_array($value->getQuestion(),$questionsSocio)) {                       
                $questionsSocio[] = $value->getQuestion();                                               
            }                                                 
        } 

        // trouve toutes les questions conditionnel
        if(!empty($questionsSocio)) {
            $conditionalQuestion = $conditionalQuestionRepository->findBy(['id'=>$questionsSocio],['number'=>'asc']);  
        }else {
            $conditionalQuestion = [];
        }

        $previousConditionalQuestion = [];

        //trouve toute les questions précédentes des questions conditionel  
        foreach($conditionalQuestion as $value) {
            $previousConditionalQuestion[] = $value->getTriggerQuestion();
        }

        //trouve les choix des réponses déclencheur aux questions conditionnel précédente de l'utilisateur
        $allAnswerConditionalQuestions = $choiceRepository->findBy(['questionChoice'=>$previousConditionalQuestion,'conditional'=>false]);
                    
        //trouve les choix de toutes les réponses aux questions déclencheur
        $answersUserSlp = $answerRepository->findBy(['userSlp'=>$userSlp,'question'=>$previousConditionalQuestion]);
                    
        //tranforme les choix persistentCollection de l'utilisateur en array
        foreach($answersUserSlp as $values) {
            $choices[] = $values->getChoices();
                    
            foreach($choices as $items) {                                                       
                $check = false;
                    foreach($items as $item) {                                  
                        if(!$item->getConditional()) {
                            $check = true;
                        }
                    }
                        if(!$check) {     
                            $item->getQuestionChoice();                           
                            //recupérer la question conditionnel par rapport au choix et l'enlever                                                                                                   
                            unset($questions[array_search($item->getQuestionChoice(), $questions)]);                                      
                        }                                             
            }                         
        }
                
        // trie le tableau $answerChoiceUserSlppar champ number dans l'array questions
        foreach ($questionsSocio as $key => $row) {
            $number[$key]  = $row->getNumber();
        }
            
        if($questionsSocio){
            array_multisort($number,SORT_ASC, $questionsSocio);
        }
                
        $form = 0;
        if($questionsSocio != null ) {
            $form = 1;             
            }   
                                                               
        return $this->render('userslp/profileSocio.html.twig',array(                
            'userslp' => $userSlp,
            'formSocio' => $form,
            'questionsSocio' => $questionsSocio
        ));    
    }
}