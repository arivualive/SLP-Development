<?php

namespace QuestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Conditional_question_free
 *
 * @ORM\Table(name="conditional_question_free")
 * @ORM\Entity(repositoryClass="QuestionBundle\Repository\Conditional_question_freeRepository")
 */
class Conditional_question_free extends Conditional_question
{

    public function __toString()
    {
        return "Conditional_question_free";
    }


    /**
     * Set survey.
     *
     * @param \QuestionBundle\Entity\Survey|null $survey
     *
     * @return Conditional_question_free
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
     * @return Conditional_question_free
     */
    public function setTheme(\QuestionBundle\Entity\Theme $theme = null)
    {
        $this->theme = $theme;

        return $this;
    }
}
