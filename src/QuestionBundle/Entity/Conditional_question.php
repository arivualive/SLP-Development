<?php

namespace QuestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use QuestionBundle\Entity\Question;

/**
 * Conditional_question
 *
 * @ORM\Table(name="conditional_question")
 * @ORM\Entity(repositoryClass="QuestionBundle\Repository\Conditional_questionRepository")
 */
abstract class Conditional_question extends Question
{

    /**
     * 
     * @ORM\OneToOne(targetEntity="Question")
     * @ORM\JoinColumn(name="question_id", referencedColumnName="id")
     */
    private $triggerQuestion;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    public function getTriggerQuestion()
    {
        return $this->triggerQuestion;
    }

    /**
     * Set triggerQuestion.
     *
     * @param \QuestionBundle\Entity\Question|null $triggerQuestion
     *
     * @return Conditional_question
     */
    public function setTriggerQuestion(\QuestionBundle\Entity\Question $triggerQuestion = null)
    {
        $this->triggerQuestion = $triggerQuestion;

        return $this;
    }

    /**
     * Set survey.
     *
     * @param \QuestionBundle\Entity\Survey|null $survey
     *
     * @return Conditional_question
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
     * @return Conditional_question
     */
    public function setTheme(\QuestionBundle\Entity\Theme $theme = null)
    {
        $this->theme = $theme;

        return $this;
    }
}
