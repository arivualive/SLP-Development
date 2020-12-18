<?php

namespace QuestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Survey
 *
 * @ORM\Table(name="survey")
 * @ORM\Entity(repositoryClass="QuestionBundle\Repository\SurveyRepository")
 */
class Survey
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;

    /**
     *
     * @ORM\OneToMany(targetEntity="Question", mappedBy="survey")
     */
    private $questions;

    /**
     *
     * @ORM\OneToMany(targetEntity="Manger", mappedBy="survey")
     */
    private $Mangers;

    public function __construct() {
        $this->questions = new ArrayCollection();
        $this->Mangers = new ArrayCollection();
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
     * Set name.
     *
     * @param string $name
     *
     * @return Survey
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Add question.
     *
     * @param \QuestionBundle\Entity\Question $question
     *
     * @return Survey
     */
    public function addQuestion(\QuestionBundle\Entity\Question $question)
    {
        $this->questions[] = $question;

        return $this;
    }

    /**
     * Remove question.
     *
     * @param \QuestionBundle\Entity\Question $question
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeQuestion(\QuestionBundle\Entity\Question $question)
    {
        return $this->questions->removeElement($question);
    }

    /**
     * Get questions.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getQuestions()
    {
        return $this->questions;
    }

    
   //-----------------------------------------------------------------------//
    
    /**
     * Add manger question.
     *
     * @param \QuestionBundle\Entity\Manger $question
     *
     * @return Survey
     */
    public function addManger(\QuestionBundle\Entity\Manger $question)
    {
        $this->questions[] = $question;

        return $this;
    }

    /**
     * Remove manger question.
     *
     * @param \QuestionBundle\Entity\Manger $question
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeManger(\QuestionBundle\Entity\Manger $question)
    {
        return $this->questions->removeElement($question);
    }

    /**
     * Get questions.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getManger()
    {
        return $this->questions;
    }
}
