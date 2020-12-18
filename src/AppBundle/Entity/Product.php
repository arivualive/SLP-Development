<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\MaxDepth;

/**
 * Produit
 *
 * @ORM\Table(name="product")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProductRepository")
 */
class Product
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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Category", fetch="EAGER")
     */
    private $category;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Subcategory", fetch="EAGER")
     */
    private $subcategory;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Brand", fetch="EAGER")
     */
    private $brand;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Bulk", fetch="EAGER")
     */
    private $bulk;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Detail", fetch="EAGER")
     */
    private $detail;

/**
     * @ORM\ManyToMany(targetEntity="Composition", fetch="EAGER")
     * @ORM\JoinTable(name="product_composition",
     *      joinColumns={@ORM\JoinColumn(name="product_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="composition_id", referencedColumnName="id")}
     *      )
     */
    private $compositions;

/**
     * @ORM\ManyToMany(targetEntity="PalmOil", fetch="EAGER")
     * @ORM\JoinTable(name="product_palm_oil",
     *      joinColumns={@ORM\JoinColumn(name="product_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="palm_oil_id", referencedColumnName="id")}
     *      )
     */
    private $palmOils;

    /**
     * @ORM\ManyToMany(targetEntity="Origin", fetch="EAGER")
     * @ORM\JoinTable(name="product_origin",
     *      joinColumns={@ORM\JoinColumn(name="product_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="origin_id", referencedColumnName="id")}
     *      )
     */
    private $origins;

    /**
     * @ORM\ManyToMany(targetEntity="HiddenSugar", fetch="EAGER")
     * @ORM\JoinTable(name="product_hidden_sugar",
     *      joinColumns={@ORM\JoinColumn(name="product_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="hidden_sugar_id", referencedColumnName="id")}
     *      )
     */
    private $hiddenSugars;

    /**
     * @ORM\ManyToMany(targetEntity="Additive", fetch="EAGER")
     * @ORM\JoinTable(name="product_additive",
     *      joinColumns={@ORM\JoinColumn(name="product_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="additive_id", referencedColumnName="id")}
     *      )
     */
    private $additives;

    /**
     * @ORM\ManyToMany(targetEntity="QualityLabel", fetch="EAGER")
     * @ORM\JoinTable(name="product_quality_label",
     *      joinColumns={@ORM\JoinColumn(name="product_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="quality_label_id", referencedColumnName="id")}
     *      )
     */
    private $qualityLabels;

    /**
     * @ORM\ManyToMany(targetEntity="Packing", fetch="EAGER")
     * @ORM\JoinTable(name="product_packing",
     *      joinColumns={@ORM\JoinColumn(name="product_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="packing_id", referencedColumnName="id")}
     *      )
     */
    private $packings;

    public function __construct()
    {
        $this->compositions = new ArrayCollection();
        // $this->details = new ArrayCollection();
        $this->palmOils = new ArrayCollection();
        $this->origins = new ArrayCollection();
        $this->hiddenSugars = new ArrayCollection();
        $this->additives = new ArrayCollection();
        $this->qualityLabels = new ArrayCollection();
        $this->packings = new ArrayCollection();
    }

    /* TO DO : getter et setters pour toutes les collections */

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
     * @return Product
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

    /* CATEGORY */

    public function setCategory(Category $category)
    {
        $this->category = $category;

        return $this;
    }

    public function getCategory()
    {
        return $this->category;
    }

    /* SUBCATEGORY */

    public function setSubcategory(Subcategory $subcategory)
    {
        $this->subcategory = $subcategory;

        return $this;
    }

    public function getSubcategory()
    {
        return $this->subcategory;
    }

    /* BULK */
    
    public function setBulk(Bulk $bulk)
    {
        $this->bulk = $bulk;

        return $this;
    }

    public function getBulk()
    {
        return $this->bulk;
    }

    /* BRAND */

    public function setBrand(Brand $brand)
    {
        $this->brand = $brand;

        return $this;
    }

    public function getBrand()
    {
        return $this->brand;
    }

    /* DETAIL */

    public function setDetail(Detail $detail)
    {
        $this->detail = $detail;

        return $this;
    }

    public function getDetail()
    {
        return $this->detail;
    }

    /* COMPOSITION */

    /**
     * @return Collection|Composition[]
     */
    public function getCompositions()
    {
        return $this->compositions;
    }

    public function addComposition(Composition $composition)
    {
        if (!$this->compositions->contains($composition)) {
            $this->compositions[] = $composition;
            // $composition->addProduct($this);
        }

        return $this;
    }

    public function removeComposition(Composition $composition)
    {
        if ($this->compositions->contains($composition)) {
            $this->compositions->removeElement($composition);
            // $composition->removeProduct($this);
        }

        return $this;
    }

    public function removeAllCompositions()
    {
        if (!empty($this->compositions)){
            $this->compositions= new ArrayCollection();
        }

        return $this;
    }

    /* PALMOIL */

    /**
     * @return Collection|PalmOil[]
     */
    public function getPalmOils()
    {
        return $this->palmOils;
    }

    public function addPalmOil(PalmOil $palmOil)
    {
        if (!$this->palmOils->contains($palmOil)) {
            $this->palmOils[] = $palmOil;
            // $palmOil->addProduct($this);
        }

        return $this;
    }

    public function removePalmOil(PalmOil $palmOil)
    {
        if ($this->palmOils->contains($palmOil)) {
            $this->palmOils->removeElement($palmOil);
            // $palmOil->removeProduct($this);
        }

        return $this;
    }

    public function removeAllPalmOils()
    {
        if (!empty($this->palmOils)){
            $this->palmOils= new ArrayCollection();
        }

        return $this;
    }

    /* ORIGIN */

    /**
     * @return Collection|Oigin[]
     */
    public function getOrigins()
    {
        return $this->origins;
    }

    public function addOrigin (Origin $origin)
    {
        if (!$this->origins->contains($origin)) {
            $this->origins[] = $origin;
            // $origin->addProduct($this);
        }

        return $this;
    }

    public function removeOrigin(Origin $origin)
    {
        if ($this->origins->contains($origin)) {
            $this->origins->removeElement($origin);
            // $origin->removeProduct($this);
        }

        return $this;
    }

    public function removeAllOrigins()
    {
        if (!empty($this->origins)){
            $this->origins= new ArrayCollection();
        }

        return $this;
    }

    /* HIDDEN SUGARS */

    /**
     * @return Collection|HiddenSugar[]
     */
    public function getHiddenSugars()
    {
        return $this->hiddenSugars;
    }

    public function addHiddenSugar(HiddenSugar $hiddenSugar)
    {
        if (!$this->hiddenSugars->contains($hiddenSugar)) {
            $this->hiddenSugars[] = $hiddenSugar;
            // $hiddenSugar->addProduct($this);
        }

        return $this;
    }

    public function removeHiddenSugar(HiddenSugar $hiddenSugar)
    {
        if ($this->hiddenSugars->contains($hiddenSugar)) {
            $this->hiddenSugars->removeElement($hiddenSugar);
            // $hiddenSugar->removeProduct($this);
        }

        return $this;
    }

    public function removeAllHiddenSugars()
    {
        if (!empty($this->hiddenSugars)){
            $this->hiddenSugars= new ArrayCollection();
        }

        return $this;
    }

    /* ADDITIVES */

    /**
     * @return Collection|Additive[]
     */
    public function getAdditives()
    {
        return $this->additives;
    }

    public function addAdditive(Additive $additive)
    {
        if (!$this->additives->contains($additive)) {
            $this->additives[] = $additive;
            // $additive->addProduct($this);
        }

        return $this;
    }

    public function removeAdditive(Additive $additive)
    {
        if ($this->additives->contains($additive)) {
            $this->additives->removeElement($additive);
            // $additive->removeProduct($this);
        }

        return $this;
    }

    public function removeAllAdditives()
    {
        if (!empty($this->additives)){
            $this->additives= new ArrayCollection();
        }

        return $this;
    }

    /* QUALITY LABELS */

    /**
     * @return Collection|QualityLabel[]
     */
    public function getQualityLabels()
    {
        return $this->qualityLabels;
    }

    public function addQualityLabel(QualityLabel $qualityLabel)
    {
        if (!$this->qualityLabels->contains($qualityLabel)) {
            $this->qualityLabels[] = $qualityLabel;
            // $qualityLabel->addProduct($this);
        }

        return $this;
    }

    public function removeQualityLabel(QualityLabel $qualityLabel)
    {
        if ($this->qualityLabels->contains($qualityLabel)) {
            $this->qualityLabels->removeElement($qualityLabel);
            // $qualityLabel->removeProduct($this);
        }

        return $this;
    }

    public function removeAllQualityLabels()
    {
        if (!empty($this->qualityLabels)){
            $this->qualityLabels= new ArrayCollection();
        }

        return $this;
    }

    /* PACKING */

    /**
     * @return Collection|Packing[]
     */
    public function getPackings()
    {
        return $this->packings;
    }

    public function addPacking(Packing $packing)
    {
        if (!$this->packings->contains($packing)) {
            $this->packings[] = $packing;
            // $packing->addProduct($this);
        }

        return $this;
    }

    public function removePacking(Packing $packing)
    {
        if ($this->packings->contains($packing)) {
            $this->packings->removeElement($packing);
            // $packing->removeProduct($this);
        }

        return $this;
    }

    public function removeAllPackings()
    {
        if (!empty($this->packings)){
            $this->packings= new ArrayCollection();
        }

        return $this;
    }
}
