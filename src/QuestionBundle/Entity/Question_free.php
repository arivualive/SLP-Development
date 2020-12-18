<?php

namespace QuestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use QuestionBundle\Entity\Question;

/**
 * Question_free
 *
 * @ORM\Table(name="question_free")
 * @ORM\Entity(repositoryClass="QuestionBundle\Repository\Question_freeRepository")
 */
class Question_free extends Question
{

    public function __toString()
    {
        return "Question_free";
    }


    /**
     * Set survey.
     *
     * @param \QuestionBundle\Entity\Survey|null $survey
     *
     * @return Question_free
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
     * @return Question_free
     */
    public function setTheme(\QuestionBundle\Entity\Theme $theme = null)
    {
        $this->theme = $theme;

        return $this;
    }
}
