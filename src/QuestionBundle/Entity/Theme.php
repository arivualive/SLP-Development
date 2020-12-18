<?php

namespace QuestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Theme
 *
 * @ORM\Table(name="theme")
 * @ORM\Entity(repositoryClass="QuestionBundle\Repository\ThemeRepository")
 */
class Theme
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="favicon", type="string", length=255)
     */
    private $favicon;

    /**
     * @var string|null
     *
     * @ORM\Column(name="transition", type="text", nullable=true)
     */
    private $transition;

    /**
     * @var string|null
     *
     * @ORM\Column(name="soustheme", type="string", length=255, nullable=true)
     */
    private $soustheme;

    /**
     *
     * @ORM\OneToMany(targetEntity="Question", mappedBy="theme")
     */
    private $questions;

    public function __construct() {
        $this->questions = new ArrayCollection();
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
     * @return Theme
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
     * Set favicon.
     *
     * @param string $favicon
     *
     * @return Theme
     */
    public function setFavicon($favicon)
    {
        $this->favicon = $favicon;

        return $this;
    }

    /**
     * Get favicon.
     *
     * @return string
     */
    public function getFavicon()
    {
        return $this->favicon;
    }

    /**
     * Set transition.
     *
     * @param string|null $transition
     *
     * @return Theme
     */
    public function setTransition($transition = null)
    {
        $this->transition = $transition;

        return $this;
    }

    /**
     * Get transition.
     *
     * @return string|null
     */
    public function getTransition()
    {
        return $this->transition;
    }

    /**
     * Set soustheme.
     *
     * @param string|null $soustheme
     *
     * @return Theme
     */
    public function setSoustheme($soustheme)
    {
        $this->soustheme = $soustheme;

        return $this;
    }

    /**
     * Get soustheme.
     *
     * @return string|null
     */
    public function getSoustheme()
    {
        return $this->soustheme;
    }

    /**
     * Add question.
     *
     * @param \QuestionBundle\Entity\Question $question
     *
     * @return Theme
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

    public function getNumberQuestion(){
        return count($this->questions);
    }
}
