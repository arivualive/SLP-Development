<?php

namespace QuestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Choice
 *
 * @ORM\Table(name="choice")
 * @ORM\Entity(repositoryClass="QuestionBundle\Repository\ChoiceRepository")
 */
class Choice
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
     * @var int
     *
     * @ORM\Column(name="conditional", type="integer")
     */
    private $conditional;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string|null
     *
     * @ORM\Column(name="comment", type="string", length=255, nullable=true)
     */
    private $comment;

    /**
     * @var string|null
     *
     * @ORM\Column(name="picture", type="string", length=255, nullable=true)
     */
    private $picture;

    /**
     * @var string|null
     *
     * @ORM\Column(name="pictureSelected", type="string", length=255, nullable=true)
     */
    private $pictureSelected;

    /**
     * @var int|null
     *
     * @ORM\Column(name="value", type="integer", nullable=true)
     */
    private $value;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Question", inversedBy="choices")
     * @ORM\JoinColumn(name="question_choice_id", referencedColumnName="id")
     */
    private $questionChoice;


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
     * Set value.
     *
     * @param int $value
     *
     * @return value
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value.
     *
     * @return int
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set conditional.
     *
     * @param int $conditional
     *
     * @return Choice
     */
    public function setConditional($conditional)
    {
        $this->conditional = $conditional;

        return $this;
    }

    /**
     * Get conditional.
     *
     * @return int
     */
    public function getConditional()
    {
        return $this->conditional;
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return Choice
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
     * Set comment.
     *
     * @param string|null $comment
     *
     * @return Choice
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment.
     *
     * @return string|null
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set picture.
     *
     * @param string|null $picture
     *
     * @return Choice
     */
    public function setPicture($picture = null)
    {
        $this->picture = $picture;

        return $this;
    }

    /**
     * Get picture.
     *
     * @return string|null
     */
    public function getPicture()
    {
        return $this->picture;
    }
/**
     * Set pictureSelected.
     *
     * @param string|null $pictureSelected
     *
     * @return Choice
     */
    public function setPictureSelected($pictureSelected = null)
    {
        $this->pictureSelected = $pictureSelected;

        return $this;
    }

    /**
     * Get pictureSelected.
     *
     * @return string|null
     */
    public function getPictureSelected()
    {
        return $this->pictureSelected;
    }
    /**
     * Set questionChoice.
     *
     * @param \QuestionBundle\Entity\Question|null $questionChoice
     *
     * @return Choice
     */
    public function setQuestionChoice(\QuestionBundle\Entity\Question $questionChoice = null)
    {
        $this->questionChoice = $questionChoice;

        return $this;
    }

    /**
     * Get questionChoice.
     *
     * @return \QuestionBundle\Entity\Question|null
     */
    public function getQuestionChoice()
    {
        return $this->questionChoice;
    }
}
