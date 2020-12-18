<?php

namespace QuestionBundle\Entity;
use QuestionBundle\Entity\theme;

use Doctrine\ORM\Mapping as ORM;

/**
 * Question
 *
 * @ORM\Table(name="question")
 * @ORM\Entity(repositoryClass="QuestionBundle\Repository\QuestionRepository")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({"questionScale" = "Question_scale", "questionFree" = "Question_free", "conditionalQuestion" = "Conditional_question", "questionChoice" = "Question_choice", "subQuestion" = "Sub_question", "questionMaster" = "Question_master", "conditionalQuestionChoice" = "Conditional_question_choice", "conditionalQuestionFree" = "Conditional_question_free", "conditionalQuestionMaster" = "Conditional_question_master", "subQuestionChoice" = "Sub_question_choice", "subQuestionFree" = "Sub_question_free", "subQuestionScale" = "Sub_question_scale"})
 */
abstract class Question
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
     * @var int|null
     *
     * @ORM\Column(name="number", type="integer", nullable=true)
     */
    protected $number;

    /**
     * @var string
     *
     * @ORM\Column(name="question", type="string", length=255)
     */
    protected $question;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="master", type="boolean", nullable=true)
     */
    protected $master;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Survey", inversedBy="questions")
     * @ORM\JoinColumn(name="survey_id", referencedColumnName="id")
     */
    protected $survey;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Theme", inversedBy="questions")
     * @ORM\JoinColumn(name="theme_id", referencedColumnName="id")
     */
    protected $theme;

    /**
     * @var string|null
     *
     * @ORM\Column(name="placeholder", type="string", length=255, nullable=true)
     */
    private $placeholder;

    /**
     * @ORM\Column(name="update_at", type="datetime" ,nullable=true)
     */
    protected $updateAt;

    /**
     * @var string|null
     *
     * @ORM\Column(name="background_left", type="string", length=255, nullable=true)
     */
    protected $backgroundLeft;

    /**
     * @var string|null
     *
     * @ORM\Column(name="background_right", type="string", length=255, nullable=true)
     */
    protected $backgroundRight;

    /**
     * Get theme.
     *
     * @return int
     */
    public function getTheme()
    {
        return $this->theme;
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
     * Set number.
     *
     * @param int|null $number
     *
     * @return Question
     */
    public function setNumber($number = null)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * Get number.
     *
     * @return int|null
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Set question.
     *
     * @param string $question
     *
     * @return Question
     */
    public function setQuestion($question)
    {
        $this->question = $question;

        return $this;
    }

    /**
     * Get question.
     *
     * @return string
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * Set master.
     *
     * @param bool|null $master
     *
     * @return Question
     */
    public function setMaster($master = null)
    {
        $this->master = $master;

        return $this;
    }

    /**
     * Get master.
     *
     * @return bool|null
     */
    public function getMaster()
    {
        return $this->master;
    }

    public function getSurvey()
    {
        return $this->survey;
    }

    /**
     * Set survey.
     *
     * @param \QuestionBundle\Entity\Survey|null $survey
     *
     * @return Question
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
     * @return Question
     */
    public function setTheme(\QuestionBundle\Entity\Theme $theme = null)
    {
        $this->theme = $theme;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getUpdateAt()
    {
        return $this->updateAt;
    }

    /**
     * @param \Datetime $param
     */
    private function setUpdatedAt(\Datetime $param)
    {
        $this->updateAt = $param;
    }

    protected function updateAt()
    {
        $this->setUpdateAt(new \DateTime());
    }


    /**
     * Get placeholder.
     *
     * @return string|null
     */
    public function getPlaceholder()
    {
        return $this->placeholder;
    }

    /**
     * Set placeholder.
     *
     * @param string|null $number
     *
     * @return Question
     */
    public function setPlaceholder($placeholder = null)
    {
        $this->placeholder = $placeholder;

        return $this;
    }

    /**
     * Get backgroundLeft.
     *
     * @return string
     */
    public function getBackgroundLeft()
    {
        return $this->backgroundLeft;
    }

    /**
     * Set backgroundRight.
     *
     * @param string $backgroundRight
     *
     * @return Question
     */
    public function setBackgroundRight($backgroundRight)
    {
        $this->backgroundRight = $backgroundRight;

        return $this;
    }

    /**
     * Get backgroundRight.
     *
     * @return string
     */
    public function getBackgroundRight()
    {
        return $this->backgroundRight;
    }
}
