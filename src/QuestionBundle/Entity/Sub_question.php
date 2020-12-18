<?php

namespace QuestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use QuestionBundle\Entity\Question;

/**
 * Sub_question
 *
 * @ORM\Table(name="sub_question")
 * @ORM\Entity(repositoryClass="QuestionBundle\Repository\Sub_questionRepository")
 */
abstract class Sub_question extends Question
{
    /**
     * 
     * @ORM\ManyToOne(targetEntity="Question_master", inversedBy="subQuestions")
     * @ORM\JoinColumn(name="question_id", referencedColumnName="id")
     */
    private $masterQuestion;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    public function getMasterQuestion()
    {
        return $this->masterQuestion;
    }

    public function getMasterConditionalQuestion()
    {
        return $this->masterConditionalQuestion;
    }

    /**
     * Set masterQuestion.
     *
     * @param \QuestionBundle\Entity\Question_master|null $masterQuestion
     *
     * @return Sub_question
     */
    public function setMasterQuestion(\QuestionBundle\Entity\Question_master $masterQuestion = null)
    {
        $this->masterQuestion = $masterQuestion;

        return $this;
    }

    /**
     * Set survey.
     *
     * @param \QuestionBundle\Entity\Survey|null $survey
     *
     * @return Sub_question
     */
    public function setSurvey(\QuestionBundle\Entity\Survey $survey = null)
    {
        $this->survey = $survey;

        return $this;
    }

    /**
     * Set theme.
     *
     * @param \QuestionBundle\Entity\Theme|null $theme
     *
     * @return Sub_question
     */
    public function setTheme(\QuestionBundle\Entity\Theme $theme = null)
    {
        $this->theme = $theme;

        return $this;
    }
}
