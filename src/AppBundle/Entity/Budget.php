<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\UserSlp;
use AppBundle\Entity\OtherRevenue;
use Doctrine\Common\Collections\ArrayCollection;
// use Symfony\Component\Routing\Annotation\Route;

/**
 * Budget
 *
 * @ORM\Table(name="budget")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\BudgetRepository")
 */
class Budget
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
     * One Budget has One UserSlp.
     * @ORM\OneToOne(targetEntity="UserSlp", inversedBy="budget")
     */
    private $userSlp;

    /**
     * One Budget has many MainRevenues
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\MainRevenue", mappedBy="budget", cascade={"persist"}, fetch="EAGER", orphanRemoval=true)
     */
    private $mainRevenues;

    /**
     * One Budget has many OtherRevenues
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\OtherRevenue", mappedBy="budget", cascade={"persist"}, fetch="EAGER", orphanRemoval=true)
     */
    private $otherRevenues;

    public function __construct()
    {
        $this->mainRevenues = new ArrayCollection();
        $this->otherRevenues = new ArrayCollection();
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
     * Set userSlp.
     *
     * @param \AppBundle\Entity\UserSlp $userSlp
     *
     * @return Budget
     */
    public function setUserSlp(UserSlp $userSlp)
    {
        $this->userSlp = $userSlp;
        return $this;
    }

    /**
     * Get userSlp.
     *
     * @return \AppBundle\Entity\UserSlp
     */
    public function getUserSlp()
    {
        return $this->userSlp;
    }

    // main revenues

    /**
     * @return Collection|MainRevenue[]
     */
    public function getMainRevenues()
    {
        return $this->mainRevenues;
    }

    public function addMainRevenue(MainRevenue $mainRevenue)
    {
        if (!$this->mainRevenues->contains($mainRevenue)) {
            $this->mainRevenues[] = $mainRevenue;
            // $mainRevenue->addBudget($this);
        }

        return $this;
    }

    public function removeMainRevenue(MainRevenue $mainRevenue)
    {
        if ($this->mainRevenues->contains($mainRevenue)) {
            $this->mainRevenues->removeElement($mainRevenue);
            // needed to update the owning side of the relationship!
            $mainRevenue->setBudget(null);
        }

        return $this;
    }

    // other revenues

    /**
     * @return Collection|OtherRevenue[]
     */
    public function getOtherRevenues()
    {
        return $this->otherRevenues;
    }

    public function addOtherRevenue(OtherRevenue $otherRevenue)
    {
        if (!$this->otherRevenues->contains($otherRevenue)) {
            $this->otherRevenues[] = $otherRevenue;
            // $otherRevenue->addBudget($this);
        }

        return $this;
    }

    public function removeOtherRevenue(OtherRevenue $otherRevenue)
    {
        if ($this->otherRevenues->contains($otherRevenue)) {
            $this->otherRevenues->removeElement($otherRevenue);
            // needed to update the owning side of the relationship!
            $otherRevenue->setBudget(null);
        }

        return $this;
    }

/*     public function __toString() {
        return "budget";
        // return $this->id;
    } */
}