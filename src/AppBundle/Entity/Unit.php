<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Unit
 *
 * @ORM\Table(name="unit")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UnitRepository")
 */
class Unit
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
     * @ORM\Column(name="abbrev", type="string", length=255)
     */
    private $abbrev;


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
     * Set abbrev.
     *
     * @param string $abbrev
     *
     * @return Unit
     */
    public function setAbbrev($abbrev)
    {
        $this->abbrev = $abbrev;

        return $this;
    }

    /**
     * Get abbrev.
     *
     * @return string
     */
    public function getAbbrev()
    {
        return $this->abbrev;
    }
}
