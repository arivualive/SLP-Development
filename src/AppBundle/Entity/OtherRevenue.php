<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Budget;
use AppBundle\Entity\Currency;
use AppBundle\Entity\Frequency;
use Doctrine\ORM\Mapping as ORM;

/**
 * OtherRevenue
 *
 * @ORM\Table(name="other_revenue")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\OtherRevenueRepository")
 */
class OtherRevenue
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
     * @ORM\Column(name="amount", type="decimal", scale=2)
     */
    private $amount;

    /**
     * @ORM\ManyToOne(targetEntity="Currency")
     * @ORM\JoinColumn(name="currency_id", referencedColumnName="id")
     */
    private $currency;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity="Frequency")
     * @ORM\JoinColumn(name="frequency_id", referencedColumnName="id")
     */
    private $frequency;

    /**
     * @ORM\ManyToOne(targetEntity="Budget", inversedBy="otherRevenues")
     */
    private $budget;

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
     * Set amount.
     *
     * @param string $amount
     *
     * @return OtherRevenue
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount.
     *
     * @return string
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set date.
     *
     * @param \DateTime $date
     *
     * @return OtherRevenue
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
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

    /**
     * Set currency.
     *
     * @param string $currency
     *
     * @return OtherRevenue
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * Get currency.
     *
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * Set frequency.
     *
     * @param string $frequency
     *
     * @return OtherRevenue
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

    /**
     * Set budget.
     *
     * @param int $budget
     *
     * @return MainRevenue
     */
    public function setBudget($budget)
    {
        $this->budget = $budget;

        return $this;
    }

    /**
     * Get budget.
     *
     * @return string
     */
    public function getBudget()
    {
        return $this->budget;
    }
}
