<?php

namespace QuestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Manger
 *
 * @ORM\Table(name="manger")
 * @ORM\Entity(repositoryClass="QuestionBundle\Repository\MangerRepository")
 */
class Manger
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Sous_processus", type="string", length=30, nullable=true)
     */
    private $sousProcessus;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Theme", type="string", length=89, nullable=true)
     */
    private $theme;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Sous_theme", type="string", length=110, nullable=true)
     */
    private $sousTheme;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Questions", type="string", length=191, nullable=true)
     */
    private $questions;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Public_concerne", type="string", length=90, nullable=true)
     */
    private $publicConcerne;

    /**
     * @var int|null
     *
     * @ORM\Column(name="Num_question", type="integer", nullable=true)
     */
    private $numQuestion;

    /**
     * @var string|null
     *
     * @ORM\Column(name="niveau1", type="string", length=140, nullable=true)
     */
    private $niveau1;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Commentaire_nv_1", type="string", length=1011, nullable=true)
     */
    private $commentaireNv1;

    /**
     * @var string|null
     *
     * @ORM\Column(name="niveau_2", type="string", length=147, nullable=true)
     */
    private $niveau2;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Commentaires_nv_2", type="string", length=1009, nullable=true)
     */
    private $commentairesNv2;

    /**
     * @var string|null
     *
     * @ORM\Column(name="niveau_3", type="string", length=142, nullable=true)
     */
    private $niveau3;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Commentaires_nv_3", type="string", length=1019, nullable=true)
     */
    private $commentairesNv3;

    /**
     * @var string|null
     *
     * @ORM\Column(name="niveau_4", type="string", length=131, nullable=true)
     */
    private $niveau4;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Commentaires_nv_4", type="string", length=1019, nullable=true)
     */
    private $commentairesNv4;

    /**
     * @var string|null
     *
     * @ORM\Column(name="niveau_5", type="string", length=128, nullable=true)
     */
    private $niveau5;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Commentaires_nv_5", type="string", length=1019, nullable=true)
     */
    private $commentairesNv5;

    /**
     * @var string|null
     *
     * @ORM\Column(name="niveau_6", type="string", length=120, nullable=true)
     */
    private $niveau6;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Commentaires_nv_6", type="string", length=1022, nullable=true)
     */
    private $commentairesNv6;

    /**
     * @var string|null
     *
     * @ORM\Column(name="png", type="string", length=7, nullable=true)
     */
    private $png;
    /**
     * @var string|null
     *
     * @ORM\Column(name="background_left", type="string", length=80, nullable=true)
     */
    private $backgroundleft;
    /**
     * @var string|null
     *
     * @ORM\Column(name="background_right", type="string", length=80, nullable=true)
     */
    private $backgroundright;
    
    /**
     *
     * @ORM\ManyToOne(targetEntity="Survey", inversedBy="Mangers")
     * @ORM\JoinColumn(name="survey_id", referencedColumnName="id")
     */
    protected $survey;


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
     * Set sousProcessus.
     *
     * @param string|null $sousProcessus
     *
     * @return Manger
     */
    public function setSousProcessus($sousProcessus = null)
    {
        $this->sousProcessus = $sousProcessus;

        return $this;
    }

    /**
     * Get sousProcessus.
     *
     * @return string|null
     */
    public function getSousProcessus()
    {
        return $this->sousProcessus;
    }

    /**
     * Set theme.
     *
     * @param string|null $theme
     *
     * @return Manger
     */
    public function setTheme($theme = null)
    {
        $this->theme = $theme;

        return $this;
    }

    /**
     * Get theme.
     *
     * @return string|null
     */
    public function getTheme()
    {
        return $this->theme;
    }

    /**
     * Set sousTheme.
     *
     * @param string|null $sousTheme
     *
     * @return Manger
     */
    public function setSousTheme($sousTheme = null)
    {
        $this->sousTheme = $sousTheme;

        return $this;
    }

    /**
     * Get sousTheme.
     *
     * @return string|null
     */
    public function getSousTheme()
    {
        return $this->sousTheme;
    }

    /**
     * Set questions.
     *
     * @param string|null $questions
     *
     * @return Manger
     */
    public function setQuestions($questions = null)
    {
        $this->questions = $questions;

        return $this;
    }

    /**
     * Get questions.
     *
     * @return string|null
     */
    public function getQuestions()
    {
        return $this->questions;
    }

    /**
     * Set publicConcerne.
     *
     * @param string|null $publicConcerne
     *
     * @return Manger
     */
    public function setPublicConcerne($publicConcerne = null)
    {
        $this->publicConcerne = $publicConcerne;

        return $this;
    }

    /**
     * Get publicConcerne.
     *
     * @return string|null
     */
    public function getPublicConcerne()
    {
        return $this->publicConcerne;
    }

    /**
     * Set numQuestion.
     *
     * @param int|null $numQuestion
     *
     * @return Manger
     */
    public function setNumQuestion($numQuestion = null)
    {
        $this->numQuestion = $numQuestion;

        return $this;
    }

    /**
     * Get numQuestion.
     *
     * @return int|null
     */
    public function getNumQuestion()
    {
        return $this->numQuestion;
    }

    /**
     * Set niveau1.
     *
     * @param string|null $niveau1
     *
     * @return Manger
     */
    public function setNiveau1($niveau1 = null)
    {
        $this->niveau1 = $niveau1;

        return $this;
    }

    /**
     * Get niveau1.
     *
     * @return string|null
     */
    public function getNiveau1()
    {
        return $this->niveau1;
    }

    /**
     * Set commentaireNv1.
     *
     * @param string|null $commentaireNv1
     *
     * @return Manger
     */
    public function setCommentaireNv1($commentaireNv1 = null)
    {
        $this->commentaireNv1 = $commentaireNv1;

        return $this;
    }

    /**
     * Get commentaireNv1.
     *
     * @return string|null
     */
    public function getCommentaireNv1()
    {
        return $this->commentaireNv1;
    }

    /**
     * Set niveau2.
     *
     * @param string|null $niveau2
     *
     * @return Manger
     */
    public function setNiveau2($niveau2 = null)
    {
        $this->niveau2 = $niveau2;

        return $this;
    }

    /**
     * Get niveau2.
     *
     * @return string|null
     */
    public function getNiveau2()
    {
        return $this->niveau2;
    }

    /**
     * Set commentairesNv2.
     *
     * @param string|null $commentairesNv2
     *
     * @return Manger
     */
    public function setCommentairesNv2($commentairesNv2 = null)
    {
        $this->commentairesNv2 = $commentairesNv2;

        return $this;
    }

    /**
     * Get commentairesNv2.
     *
     * @return string|null
     */
    public function getCommentairesNv2()
    {
        return $this->commentairesNv2;
    }

    /**
     * Set niveau3.
     *
     * @param string|null $niveau3
     *
     * @return Manger
     */
    public function setNiveau3($niveau3 = null)
    {
        $this->niveau3 = $niveau3;

        return $this;
    }

    /**
     * Get niveau3.
     *
     * @return string|null
     */
    public function getNiveau3()
    {
        return $this->niveau3;
    }

    /**
     * Set commentairesNv3.
     *
     * @param string|null $commentairesNv3
     *
     * @return Manger
     */
    public function setCommentairesNv3($commentairesNv3 = null)
    {
        $this->commentairesNv3 = $commentairesNv3;

        return $this;
    }

    /**
     * Get commentairesNv3.
     *
     * @return string|null
     */
    public function getCommentairesNv3()
    {
        return $this->commentairesNv3;
    }

    /**
     * Set niveau4.
     *
     * @param string|null $niveau4
     *
     * @return Manger
     */
    public function setNiveau4($niveau4 = null)
    {
        $this->niveau4 = $niveau4;

        return $this;
    }

    /**
     * Get niveau4.
     *
     * @return string|null
     */
    public function getNiveau4()
    {
        return $this->niveau4;
    }

    /**
     * Set commentairesNv4.
     *
     * @param string|null $commentairesNv4
     *
     * @return Manger
     */
    public function setCommentairesNv4($commentairesNv4 = null)
    {
        $this->commentairesNv4 = $commentairesNv4;

        return $this;
    }

    /**
     * Get commentairesNv4.
     *
     * @return string|null
     */
    public function getCommentairesNv4()
    {
        return $this->commentairesNv4;
    }

    /**
     * Set niveau5.
     *
     * @param string|null $niveau5
     *
     * @return Manger
     */
    public function setNiveau5($niveau5 = null)
    {
        $this->niveau5 = $niveau5;

        return $this;
    }

    /**
     * Get niveau5.
     *
     * @return string|null
     */
    public function getNiveau5()
    {
        return $this->niveau5;
    }

    /**
     * Set commentairesNv5.
     *
     * @param string|null $commentairesNv5
     *
     * @return Manger
     */
    public function setCommentairesNv5($commentairesNv5 = null)
    {
        $this->commentairesNv5 = $commentairesNv5;

        return $this;
    }

    /**
     * Get commentairesNv5.
     *
     * @return string|null
     */
    public function getCommentairesNv5()
    {
        return $this->commentairesNv5;
    }

    /**
     * Set niveau6.
     *
     * @param string|null $niveau6
     *
     * @return Manger
     */
    public function setNiveau6($niveau6 = null)
    {
        $this->niveau6 = $niveau6;

        return $this;
    }

    /**
     * Get niveau6.
     *
     * @return string|null
     */
    public function getNiveau6()
    {
        return $this->niveau6;
    }

    /**
     * Set commentairesNv6.
     *
     * @param string|null $commentairesNv6
     *
     * @return Manger
     */
    public function setCommentairesNv6($commentairesNv6 = null)
    {
        $this->commentairesNv6 = $commentairesNv6;

        return $this;
    }

    /**
     * Get commentairesNv6.
     *
     * @return string|null
     */
    public function getCommentairesNv6()
    {
        return $this->commentairesNv6;
    }

    /**
     * Set png.
     *
     * @param string|null $png
     *
     * @return Manger
     */
    public function setPng($png = null)
    {
        $this->png = $png;

        return $this;
    }

    /**
     * Get png.
     *
     * @return string|null
     */
    public function getPng()
    {
        return $this->png;
    }

    /**
     * Set background_left
     *
     * @param string|null $background_left
     *
     * @return Manger
     */
    public function setBackgroundleft($backgroundleft = null)
    {
        $this->backgroundleft = $backgroundleft;

        return $this;
    }

    /**
     * Get background_left.
     *
     * @return string|null
     */
    public function getBackgroundleft()
    {
        return $this->backgroundleft;
    }
    /**
     * Set background_right 
     *
     * @param string|null $background_right
     *
     * @return Manger
     */
    public function setBackgroundright($backgroundright = null)
    {
        $this->backgroundright = $backgroundright;

        return $this;
    }

    /**
     * Get background_right.
     *
     * @return string|null
     */
    public function getBackgroundright()
    {
        return $this->backgroundright;
    }

    /**
     *
     * Get survey
     * 
     */


    public function getSurvey()
    {
        return $this->survey;
    }

    

    /**
     * Set survey.
     *
     * @param \QuestionBundle\Entity\Survey|null $survey
     *
     * @return Manger
     */
    public function setSurvey(\QuestionBundle\Entity\Survey $survey = null)
    {
        $this->survey = $survey;

        return $this;
    }
}
