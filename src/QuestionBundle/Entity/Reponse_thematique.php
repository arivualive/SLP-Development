<?php

namespace QuestionBundle\Entity;
use Doctrine\ORM\Mapping as ORM;



/**
 * Reponse_thematique
 *
 * @ORM\Table(name="reponse_thematique")
 * @ORM\Entity(repositoryClass="QuestionBundle\Repository\Reponse_thematiqueRepository")
 * 
 */

 class  Reponse_thematique
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
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\UserSlp", inversedBy="Reponsethematique")
     * @ORM\JoinColumn(name="user_slp_id", referencedColumnName="id")
     */
    protected $userSlp;

    /**
     * @ORM\ManyToOne(targetEntity="Manger")
     * @ORM\JoinColumn(name="manger_id", referencedColumnName="id")
     */
    private $manger;

      /**
     * @var int|null
     *
     * @ORM\Column(name="value", type="integer", nullable=true)
     */
    private $value;

    
     /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
    
    

    public function setManger($manger)
    {
        $this->manger = $manger;

        return $this;
    }
    
    public function getManger()
    {
        return $this->manger;
    }

    /**
     * Get userSlp.
     *
     * @return \AppBundle\Entity\UserSlp|null
     */
    public function getUserSlp()
    {
        return $this->userSlp;
    }
    public function setUserSlp($user)
    {
        $this->userSlp = $user;

        return $this;
    }

     /**
     * set value.
     *
     * @return int
     */
    public function setValue($value)
    {
         $this->value = $value;
         return $this;

    }
     
    /**
     * Get value.
     *
     * @return int
     */
    public function getValue()
    {
        
        return $this->value;
    }

    
}
