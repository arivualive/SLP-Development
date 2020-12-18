<?php

namespace QuestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Scale_type
 *
 * @ORM\Table(name="scale_type")
 * @ORM\Entity(repositoryClass="QuestionBundle\Repository\Scale_typeRepository")
 */
class Scale_type
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
     * @var int
     *
     * @ORM\Column(name="choiceNumber", type="integer")
     */
    private $choiceNumber;


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
     * @return Scale_type
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
     * Set choiceNumber.
     *
     * @param int $choiceNumber
     *
     * @return Scale_type
     */
    public function setChoiceNumber($choiceNumber)
    {
        $this->choiceNumber = $choiceNumber;

        return $this;
    }

    /**
     * Get choiceNumber.
     *
     * @return int
     */
    public function getChoiceNumber()
    {
        return $this->choiceNumber;
    }
}
