<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use QuestionBundle\Entity\Survey;

/**
 * SurveyProgress
 *
 * @ORM\Table(name="survey_progress")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SurveyProgressRepository")
 */
class SurveyProgress
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="UserSlp", inversedBy="surveyProgress")
     * @ORM\JoinColumn(name="userSlp_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="QuestionBundle\Entity\Survey")
     * @ORM\JoinColumn(name="survey_id", referencedColumnName="id")
     */
    private $survey;

    /**
     * @ORM\ManyToOne(targetEntity="QuestionBundle\Entity\Question")
     * @ORM\JoinColumn(name="question_id", referencedColumnName="id")
     */
    private $lastAnwseredQuestion;

    /**
     * @var bool
     *
     * @ORM\Column(name="done", type="boolean")
     */
    private $done;


    /**
     * Get id.
     *
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set user.
     *
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user.
     *
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set survey.
     *
     */
    public function setSurvey($survey)
    {
        $this->survey = $survey;

        return $this;
    }

    /**
     * Get survey.
     *
     */
    public function getSurvey()
    {
        return $this->survey;
    }

     /**
     * Set lastAnwseredQuestion.
     *
     */
    public function setLastAnwseredQuestion($lastAnwseredQuestion)
    {
        $this->lastAnwseredQuestion = $lastAnwseredQuestion;

        return $this;
    }

    /**
     * Get lastAnwseredQuestion.
     *
     */
    public function getLastAnwseredQuestion()
    {
        return $this->lastAnwseredQuestion;
    }

    /**
     * Set done.
     *
     * @param bool $done
     *
     * @return SurveyProgress
     */
    public function setDone($done)
    {
        $this->done = $done;

        return $this;
    }

    /**
     * Get done.
     *
     * @return bool
     */
    public function getDone()
    {
        return $this->done;
    }
}
