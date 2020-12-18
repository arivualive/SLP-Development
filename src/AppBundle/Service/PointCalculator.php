<?php
// src/AppBundle/Service/PointCalculator.php
namespace AppBundle\Service;

use AppBundle\Entity\SubQuestion;
use AppBundle\Entity\Answer;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PointCalculator extends Controller
{
    public function CalculatorQ24S1($user, $answerRepo, $subQuestionRepo, $question)
    {
        $subQuestions = $subQuestionRepo->findByQuestion($question);

        $val1 = 0;
        $val2 = 0;
        $val3 = 0;
        $val4 = 0;
        $val5 = 0;
        $val6 = 0;
        $val7 = 0;
        $val8 = 0;
        $val9 = 0;
        $val10 = 0;

        $indexVal = 1;

        foreach ($subQuestions as $subQuestion) {
            $answer = $answerRepo->findElderSubAnswer($subQuestion, $user);
            $point = $answer[0]->getNotationChoiceAnswer();

            if ($answer[0]->getAnswerType()->getId() == 5) {
                switch ($point) {
                    case 1:
                        ${'val' . $indexVal} = -2; 
                        break;
                    case 2:
                        ${'val' . $indexVal} = -1;
                        break;
                    case 3:
                        ${'val' . $indexVal} = 0;
                        break;
                    case 4:
                        ${'val' . $indexVal} = 1;
                        break;
                    case 5:
                        ${'val' . $indexVal} = 2;
                        break;
                }
                $indexVal ++;
            } elseif ($answer[0]->getAnswerType()->getId() == 7) {
                switch ($point) {
                    case 1:
                        ${'val' . $indexVal} = 2;
                        break;
                    case 2:
                        ${'val' . $indexVal} = 1;
                        break;
                    case 3:
                        ${'val' . $indexVal} = 0;
                        break;
                    case 4:
                        ${'val' . $indexVal} = -1;
                        break;
                    case 5:
                        ${'val' . $indexVal} = -2;
                        break;
                }
                $indexVal ++;
            }
        }

        $moyenne1 = ($val1 + $val2 + $val3 + $val4 + $val5 + $val6) / 6; 
        $moyenne2 = ($val3 + $val4 + $val5 + $val6 + $val7 + $val8) / 6;
        $moyenne3 = ($val5 + $val6 + $val7 + $val8 + $val9 + $val10) / 6;


        if ($moyenne1 >= 0 && $moyenne2 >= 0 && $moyenne3 >= 0) {
            $userProfileId = 1;
        }elseif ($moyenne1 >= 0 && $moyenne2 >= 0 && $moyenne3 < 0) {
            $userProfileId = 2;
        }elseif ($moyenne1 >= 0 && $moyenne2 < 0 && $moyenne3 >= 0) {
            $userProfileId = 3;
        }elseif ($moyenne1 >= 0 && $moyenne2 < 0 && $moyenne3 < 0) {
            $userProfileId = 4;
        }elseif ($moyenne1 < 0 && $moyenne2 >= 0 && $moyenne3 >= 0) {
            $userProfileId = 5;
        }elseif ($moyenne1 < 0 && $moyenne2 >= 0 && $moyenne3 < 0) {
            $userProfileId = 6;
        }elseif ($moyenne1 < 0 && $moyenne2 < 0 && $moyenne3 >= 0) {
            $userProfileId = 7;
        }elseif ($moyenne1 < 0 && $moyenne2 < 0 && $moyenne3 < 0) {
            $userProfileId = 8;
        }

        return $userProfileId;
    }

    public function CalculatorQ2S1($date)
    {

        if ($date < 1925) {
            $generationProfileId = 1;
        }elseif ( 1925 <= $date && $date < 1943 ) {
            $generationProfileId = 2;
        }elseif (1943 <= $date && $date < 1959) {
            $generationProfileId = 6;
        }elseif (1959 <= $date && $date < 1978) {
            $generationProfileId = 3;
        }elseif (1978 <= $date && $date < 1995) {
            $generationProfileId = 4;
        }elseif (1995 <= $date) {
            $generationProfileId = 5;
        }

        return $generationProfileId;
    }
}