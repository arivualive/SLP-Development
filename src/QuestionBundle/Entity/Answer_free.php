<?php

namespace QuestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use QuestionBundle\Entity\Answer;

/**
 * Answer_free
 *
 * @ORM\Table(name="answer_free")
 * @ORM\Entity(repositoryClass="QuestionBundle\Repository\Answer_freeRepository")
 */
class Answer_free extends Answer
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
     * @var string
     *
     * @ORM\Column(name="free_aswere", type="string", length=255)
     */
    protected $freeAswere;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
    public function __construct() {
        $this->updateAt();
    }
    /**
     * Set freeAswere.
     *
     * @param string $freeAswere
     *
     * @return Answer_free
     */
    public function setFreeAswere($freeAswere)
    {
        $this->freeAswere = $freeAswere;

        return $this;
    }

    /**
     * Get freeAswere.
     *
     * @return string
     */
    public function getFreeAswere()
    {
        return $this->freeAswere;
    }

    public function __toString()
    {
        return "free";
    }
}
