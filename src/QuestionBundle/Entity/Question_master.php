<?php

namespace QuestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Question_master
 *
 * @ORM\Table(name="question_master")
 * @ORM\Entity(repositoryClass="QuestionBundle\Repository\Question_masterRepository")
 */
class Question_master extends Question
{
    /**
     * 
     * @ORM\OneToMany(targetEntity="Sub_question", mappedBy="masterQuestion")
     * @ORM\JoinColumn(name="sub_question_id", referencedColumnName="id")
     */
    private $subQuestions;

    public function __construct() {
        $this->subQuestions = new ArrayCollection();
    }

    public function __toString()
    {
        return "Question_master";
    }

    public function getSubQuestions() {

        return $this->subQuestions;

    }

    /**
     * Add subQuestion.
     *
     * @param \QuestionBundle\Entity\Sub_question $subQuestion
     *
     * @return Question_master
     */
    public function addSubQuestion(\QuestionBundle\Entity\Sub_question $subQuestion)
    {
        $this->subQuestions[] = $subQuestion;

        return $this;
    }

    /**
     * Remove subQuestion.
     *
     * @param \QuestionBundle\Entity\Sub_question $subQuestion
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeSubQuestion(\QuestionBundle\Entity\Sub_question $subQuestion)
    {
        return $this->subQuestions->removeElement($subQuestion);
    }

    /**
     * Set survey.
     *
     * @param \QuestionBundle\Entity\Survey|null $survey
     *
     * @return Question_master
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
     * @return Question_master
     */
    public function setTheme(\QuestionBundle\Entity\Theme $theme = null)
    {
        $this->theme = $theme;

        return $this;
    }
}