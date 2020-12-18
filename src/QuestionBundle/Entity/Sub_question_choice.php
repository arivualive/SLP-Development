<?php

namespace QuestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Sub_question_choice
 *
 * @ORM\Table(name="sub_question_choice")
 * @ORM\Entity(repositoryClass="QuestionBundle\Repository\Sub_question_choiceRepository")
 */
class Sub_question_choice extends Sub_question
{
    /**
     * @var bool
     *
     * @ORM\Column(name="is_unique", type="boolean")
     */
    private $isUnique;


    

    /**
     *
     * @ORM\OneToMany(targetEntity="Choice", mappedBy="questionChoice")
     */
    private $choices;

    public function __construct() {
        $this->choices = new ArrayCollection();
    }

    public function __toString()
    {
        return "Sub_question_choice";
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
     * Set isUnique.
     *
     * @param bool $isUnique
     *
     * @return Question_choice
     */
    public function setIsUnique($isUnique)
    {
        $this->isUnique = $isUnique;

        return $this;
    }

    /**
     * Get isUnique.
     *
     * @return bool
     */
    public function getIsUnique()
    {
        return $this->isUnique;
    }

    public function getChoices()
    {
        return $this->choices;
    }

    /**
     * Add choice.
     *
     * @param \QuestionBundle\Entity\Choice $choice
     *
     * @return Sub_question_choice
     */
    public function addChoice(\QuestionBundle\Entity\Choice $choice)
    {
        $this->choices[] = $choice;

        return $this;
    }

    /**
     * Remove choice.
     *
     * @param \QuestionBundle\Entity\Choice $choice
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeChoice(\QuestionBundle\Entity\Choice $choice)
    {
        return $this->choices->removeElement($choice);
    }

    /**
     * Set survey.
     *
     * @param \QuestionBundle\Entity\Survey|null $survey
     *
     * @return Sub_question_choice
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
     * @return Sub_question_choice
     */
    public function setTheme(\QuestionBundle\Entity\Theme $theme = null)
    {
        $this->theme = $theme;

        return $this;
    }
}
