<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * BudgetTheme
 *
 * @ORM\Table(name="budget_theme")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\BudgetThemeRepository")
 */
class BudgetTheme
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
     * @ORM\Column(name="image", type="text")
     */
    private $image;

    /**
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Category", mappedBy="theme")
     * @ORM\JoinColumn(name="theme_id", referencedColumnName="id")
     */
    protected $categories;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
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
     * @return BudgetTheme
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
     * @return BudgetTheme
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
     * Add category.
     *
     * @param \AppBundle\Entity\Category $subcategory
     *
     * @return Category
     */
    public function addCategory(\AppBundle\Entity\Category $category)
    {
        $this->categories[] = $category;
        return $this;
    }

    /**
     * Remove category.
     *
     * @param \AppBundle\Entity\Category $category
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeCategory(\AppBundle\Entity\Category $category)
    {
        return $this->categories->removeElement($category);
    }

    /**
     * Get categories.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCategories()
    {
        return $this->categories;
    }
}
