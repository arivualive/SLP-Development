<?php

namespace QuestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Sub_question_scale
 *
 * @ORM\Table(name="sub_question_scale")
 * @ORM\Entity(repositoryClass="QuestionBundle\Repository\Sub_question_scaleRepository")
 */
class Sub_question_scale extends Sub_question
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     *
     * @ORM\ManyToOne(targetEntity="QuestionBundle\Entity\Scale_type")
     * @ORM\JoinColumn(name="scale_type_id", referencedColumnName="id")
     */
    private $scaleType;

    public function __toString()
    {
        return "Sub_question_scale";
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set scaleType.
     *
     * @return Question_scale
     */
    public function setScaleType($scaleType)
    {
        $this->scaleType = $scaleType;

        return $this;
    }

    /**
     * Get scaleType.
     *
     */
    public function getScaleType()
    {
        return $this->scaleType;
    }

    /**
     * Set survey.
     *
     * @param \QuestionBundle\Entity\Survey|null $survey
     *
     * @return Sub_question_scale
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
     * @return Sub_question_scale
     */
    public function setTheme(\QuestionBundle\Entity\Theme $theme = null)
    {
        $this->theme = $theme;

        return $this;
    }
}
