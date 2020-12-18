<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\UserSlp;
use AppBundle\Entity\Product;
use AppBundle\Entity\Cost;
use AppBundle\Entity\Quantity;
use AppBundle\Entity\Currency;
use AppBundle\Entity\Frequency;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Spending
 *
 * @ORM\Table(name="spending")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SpendingRepository")
 */
class Spending
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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\UserSlp", inversedBy="spendings")
     * @ORM\JoinColumn(name="user_slp_id", referencedColumnName="id")
     */
    private $userSlp;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Product")
     */
    private $product;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Cost")
     */
    private $cost;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Quantity")
     */
    private $quantity;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Currency")
     */
    private $currency;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Frequency")
     */
    private $frequency;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

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
     * Set date.
     *
     * @param \DateTime $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * Get date.
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    public function setCost(Cost $cost)
    {
        $this->cost = $cost;

        return $this;
    }

    public function getCost()
    {
        return $this->cost;
    }

    public function setCurrency(Currency $currency)
    {
        $this->currency = $currency;

        return $this;
    }

    public function getCurrency()
    {
        return $this->currency;
    }

    public function setQuantity(Quantity $quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getQuantity()
    {
        return $this->quantity;
    }

    public function setFrequency(Frequency $frequency)
    {
        $this->frequency = $frequency;

        return $this;
    }

    public function getFrequency()
    {
        return $this->frequency;
    }

    public function setProduct(Product $product)
    {
        $this->product = $product;

        return $this;
    }

    public function getProduct()
    {
        return $this->product;
    }
    
    public function setUserSlp(UserSlp $userSlp)
    {
        $this->userSlp = $userSlp;

        return $this;
    }

    public function getUserSlp()
    {
        return $this->userSlp;
    }

    public function setBrand(Brand $brand) 
    {
        $this->brand = $brand;

        return $this;
    }

    public function getBrand() 
    {
        return $this->brand;
    }
}
