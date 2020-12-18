<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Quantity
 *
 * @ORM\Table(name="quantity")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\QuantityRepository")
 */
class Quantity
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
     * @ORM\Column(name="number", type="decimal", scale=2)
     */
    private $number;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Unit")
     */
    private $unit;

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
     * @param string $number
     *
     * @return Quantity
     */
    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * Get number.
     *
     * @return string
     */
    public function getNumber()
    {
        return $this->number;
    }


    public function getUnit()
    {
        return $this->unit;
    }

    public function setUnit(Quantity $unit)
    {
        $this->unit = $unit;

        return $this;
    }
}
