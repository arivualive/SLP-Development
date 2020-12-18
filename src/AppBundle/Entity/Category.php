<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Category
 *
 * @ORM\Table(name="category")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CategoryRepository")
 */
class Category
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
     * @ORM\Column(name="image", type="string", length=255)
     */
    private $image;

    /**
     * @ORM\OneToMany(targetEntity="Subcategory", mappedBy="category", cascade={"persist"}, fetch="EAGER", orphanRemoval=true)
     */
    private $subcategories;

    /**
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\BudgetTheme", inversedBy="categories")
     * @ORM\JoinColumn(name="theme_id", referencedColumnName="id")
     */
    protected $theme;

    /**
     * @ORM\ManyToOne(targetEntity="Frequency")
     */
    private $frequency;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->subcategories = new ArrayCollection();
        // $this->frequencies = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Category
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
     * Set image.
     *
     * @param string $image
     *
     * @return Category
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image.
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set theme.
     *
     * @param \AppBundle\Entity\BudgetTheme|null $theme
     *
     * @return Category
     */
    public function setTheme(\AppBundle\Entity\BudgetTheme $theme = null)
    {
        $this->theme = $theme;

        return $this;
    }

    /**
     * Get theme.
     *
     * @return \AppBundle\Entity\BudgetTheme|null
     */
    public function getTheme()
    {
        return $this->theme;
    }

    /**
     * @return Collection|Subcategory[]
     */
    public function getSubcategories()
    {
        return $this->subcategories;
    }

    public function addSubcategory(Subcategory $subcategory)
    {
        if (!$this->subcategories->contains($subcategory)) {
            $this->subcategories[] = $subcategory;
            // $subcategory->addCategory($this);
        }

        return $this;
    }

    public function removeSubcategory(Subcategory $subcategory)
    {
        if ($this->subcategories->contains($subcategory)) {
            $this->subcategories->removeElement($subcategory);
            // needed to update the owning side of the relationship!
            $subcategory->setCategory(null);
        }

        return $this;
    }


    /**
     * Set frequency.
     *
     * @param string $frequency
     *
     * @return Category
     */
    public function setFrequency($frequency)
    {
        $this->frequency = $frequency;

        return $this;
    }

    /**
     * Get frequency.
     *
     * @return string
     */
    public function getFrequency()
    {
        return $this->frequency;
    }
}
