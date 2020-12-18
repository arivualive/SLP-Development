<?php

namespace QuestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use QuestionBundle\Entity\Answer;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Answer_choice
 *
 * @ORM\Table(name="answer_choice")
 * @ORM\Entity(repositoryClass="QuestionBundle\Repository\Answer_choiceRepository")
 */
class Answer_choice extends Answer
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
     * @ORM\ManyToMany(targetEntity="Choice")
     * @ORM\JoinTable(name="answers_choices",
     *      joinColumns={@ORM\JoinColumn(name="answer_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="choice_id", referencedColumnName="id")}
     *      )
     */
    private $choices;

    public function __construct() {
        $this->choices = new ArrayCollection();
        $this->updateAt();
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

    public function __toString()
    {
        return "choice";
    }

    public function getChoices()
    {
        return $this->choices;
    }

    public function setChoices($choice)
    {
        $this->choices[] = $choice;
    }


    /**
     * Add choice.
     *
     * @param \QuestionBundle\Entity\Choice $choice
     *
     * @return Answer_choice
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
}
