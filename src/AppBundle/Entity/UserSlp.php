<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use QuestionBundle\Entity\Answer;
use QuestionBundle\Entity\Reponse_thematique;
use QuestionBundle\Entity\Answer_free;
use AppBundle\Entity\Spending;
use AppBundle\Entity\Depenses;
use AppBundle\Entity\Personnalisation;
use AppBundle\Entity\UserProfil;


/**
 * UserSlp
 *
 * @ORM\Table(name="user_slp")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserSlpRepository")
 */
class UserSlp
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
     * One userSlp has One Budget.
     * @ORM\OneToOne(targetEntity="Budget", mappedBy="userSlp")
     */
    private $budget;

    /**
     * @var int
     *
     * @ORM\Column(name="gaeaUserId", type="integer", unique=true)
     */
    private $gaeaUserId;

    /**
     * @var int
     *
     * 0 = user normal, 1 = admin, 2 = super admin
     * @ORM\Column(name="roleSlp", type="integer")
     */
    private $roleSlp;

    /**
     * @var boolean
     *
     * @ORM\Column(name="slc", type="boolean", nullable=true)
     */
    private $slc;

    /**
     * 
     *
     * @ORM\Column(name="last_login", type="datetime", nullable=true)
     */
    private $lastlogin;

    /**
     *
     * @ORM\OneToMany(targetEntity="QuestionBundle\Entity\Reponse_thematique", mappedBy="userSlp")
     */
    private $Reponsethematique;

    /**
     *
     * @ORM\OneToMany(targetEntity="QuestionBundle\Entity\Answer", mappedBy="userSlp")
     */
    private $answers;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Spending", mappedBy="userSlp")
     */
    private $spendings;

    /**
     * @ORM\OneToMany(targetEntity="SurveyProgress", mappedBy="user")
     * @ORM\JoinColumn(name="surveyProgress_id", referencedColumnName="id")
     */
    private $surveyProgress;

    /**
     * @ORM\OneToOne(targetEntity="UserProfil", mappedBy="userSlp")
     * @ORM\JoinColumn(name="userProfil_id", referencedColumnName="id")
     */
    private $profil;
    
    public function __construct()
    {
        $this->answers = new ArrayCollection();
        $this->Reponse_thematique = new ArrayCollection();
        $this->spendings = new ArrayCollection();
    }

    /**
     * 
     * @ORM\OneToOne(targetEntity="Personalisation", mappedBy="userSlp")
     * @ORM\JoinColumn(name="personalisation_id", referencedColumnName="id", nullable=true)
     */
     private $personalisationId;

    /**
     * Set depenses.
     * @return Collection|Spending[]
     * @param string $depenses
     *
     * @return UserSlp
     */
    public function getSpendings()
    {
        return $this->spendings;
    }

    public function addSpending(Spending $spending)
    {
        if (!$this->spendings->contains($spending)) {
            $this->spendings[] = $spending;
            // $spending->addUserSlp($this);
        }

        return $this;
    }

    public function removeSpending(Spending $spending)
    {
        if ($this->spendings->contains($spending)) {
            $this->spendings->removeElement($spending);
            // $spending->removeUserSlp($this);
        }

        return $this;
    }

    /**
     * Set budget.
     *
     * @param string $budget
     *
     * @return UserSlp
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
     * Get answers.
     *
     * @return ArrayCollection
     */
    public function getAnswers()
    {
        return $this->answers;
    }

    /**
     * Set gaeaUserId.
     *
     * @param int $gaeaUserId
     *
     * @return UserSlp
     */
    public function setGaeaUserId($gaeaUserId)
    {
        $this->gaeaUserId = $gaeaUserId;

        return $this;
    }

    /**
     * Get gaeaUserId.
     *
     * @return int
     */
    public function getGaeaUserId()
    {
        return $this->gaeaUserId;
    }


    public function getRoleSlp()
    {
        return $this->roleSlp;
    }

    public function setRoleSlp($roleSlp)
    {

        $this->roleSlp = $roleSlp;

        return $this;
    }

    public function getSurveyProgress()
    {
        return $this->surveyProgress;
    }

    /**
     * Get slc
     *
     * @return bool
     */
    public function getSlc()
    {
        return $this->slc;
    }

    /**
     * Set slc
     *
     * @param BooleanType $slc
     * @return User
     */
    public function setSlc($slc)
    {
        $this->slc = $slc;

        return $this;
    }

    /**
     * get last login
     * 
     *@var datetime
     * ORM\Column(name="last_login", type="datetime", nullable=true)
     *
     */
    public function getLastLogin()
    {
       return $this->lastlogin;
    }


     /**
     *  set last login
     * 
     * @var datetime
     * ORM\Column(name="last_login", type="datetime", nullable=true)
     *
     */
    public function setLastLogin()
    {
        $this->lastlogin = new \DateTime("now");
    }

    /**
     * Set personalisation.
     *
     * @param int $personalisationId
     */
    public function setPersonalisationId($personalisationId)
    {
        $this->personalisationId = $personalisationId;

        return $this;
    }

    /**
     * Get personalisation.
     *
     * @return int
     */
    public function getPersonalisationId()
    {
        return $this->personalisationId;
    }
    
     /**
     * Get profil.
     *
     * @return \AppBundle\Entity\UserProfil
     */   
    function getProfil() {
        return $this->profil;
    }
    /**
     * Set profil.
     *
     * @param \AppBundle\Entity\UserProfil
     * @return \AppBundle\Entity\UserSlp
     */
    function setProfil($profil) {
        $this->profil = $profil;
    }


}
