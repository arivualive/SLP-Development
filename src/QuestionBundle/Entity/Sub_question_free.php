<?php

namespace QuestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Sub_question_free
 *
 * @ORM\Table(name="sub_question_free")
 * @ORM\Entity(repositoryClass="QuestionBundle\Repository\Sub_question_freeRepository")
 */
class Sub_question_free extends Sub_question
{

    public function __toString()
    {
        return "Sub_question_free";
    }


    /**
     * Set survey.
     *
     * @param \QuestionBundle\Entity\Survey|null $survey
     *
     * @return Sub_question_free
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
     * @return Sub_question_free
     */
    public function setTheme(\QuestionBundle\Entity\Theme $theme = null)
    {
        $this->theme = $theme;

        return $this;
    }
}
