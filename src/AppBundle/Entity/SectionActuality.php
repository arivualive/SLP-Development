<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SectionActuality
 *
 * @ORM\Table(name="section_actuality")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SectionActualityRepository")
 */
class SectionActuality
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
     * @var array
     *
     * @ORM\Column(name="section", type="array")
     */
    private $section;



    /**
     * @ORM\Column(name="url", type="string", length=255)
     */
    private $url;

    /**
     * @ORM\Column(name="alt", type="string", length=255)
     */
    private $alt;


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
     * Set section.
     *
     * @param array $section
     *
     * @return SectionActuality
     */
    public function setSection($section)
    {
        $this->section = $section;

        return $this;
    }

    /**
     * Get section.
     *
     * @return array
     */
    public function getSection()
    {
        return $this->section;
    }



    /**
     * Set url.
     *
     * @param string $url
     *
     * @return SectionActuality
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url.
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set alt.
     *
     * @param string $alt
     *
     * @return SectionActuality
     */
    public function setAlt($alt)
    {
        $this->alt = $alt;

        return $this;
    }

    /**
     * Get alt.
     *
     * @return string
     */
    public function getAlt()
    {
        return $this->alt;
    }


}
