<?php

namespace QuestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use QuestionBundle\Entity\Answer;

/**
 * Answer_scale
 *
 * @ORM\Table(name="answer_scale")
 * @ORM\Entity(repositoryClass="QuestionBundle\Repository\Answer_scaleRepository")
 */
class Answer_scale extends Answer
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
     * @var int
     *
     * @ORM\Column(name="scale", type="integer")
     */
    private $scale;
    public function __construct() {
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

    /**
     * Set scale.
     *
     * @param int $scale
     *
     * @return Answer_scale
     */
    public function setScale($scale)
    {
       //je set les valeurs pour le questionnaire mini-profil
        if ($scale == 4) {
             $scale = 0;
        }elseif ($scale == 3) {
             $scale = 1;
        }elseif ($scale == 2) {
             $scale = 2;
        }elseif ($scale == 1) {
             $scale = 3;
        }
        $this->scale = $scale;

        return $this;
    }

    /**
     * Get scale.
     *
     * @return int
     */
    public function getScale()
    {
        return $this->scale;
    }

    public function __toString()
    {
        return "scale";
    }
}
