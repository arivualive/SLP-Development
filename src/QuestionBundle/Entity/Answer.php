<?php

namespace QuestionBundle\Entity;

use AppBundle\Entity\UserSlp;
use Doctrine\ORM\Mapping as ORM;

/**
 * Answer
 *
 * @ORM\Table(name="answer")
 * @ORM\Entity(repositoryClass="QuestionBundle\Repository\AnswerRepository")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({"answerScale" = "Answer_scale", "answerChoice" = "Answer_choice", "answerFree" = "Answer_free"})
 */
abstract class Answer
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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\UserSlp", inversedBy="answers")
     * @ORM\JoinColumn(name="user_slp_id", referencedColumnName="id")
     */
    protected $userSlp;

    /**
     * @ORM\ManyToOne(targetEntity="Question")
     * @ORM\JoinColumn(name="question_id", referencedColumnName="id")
     */
    private $question;

    /**
     * @var @ORM\Column(name="update_at", type="datetime" ,nullable=true)
     */
    protected $updateAt;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    public function setUserSlp($user)
    {
        $this->userSlp = $user;

        return $this;
    }

    public function setQuestion($question)
    {
        $this->question = $question;

        return $this;
    }

    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * Get userSlp.
     *
     * @return UserSlp|null
     */
    public function getUserSlp()
    {
        return $this->userSlp;
    }

    /**
     * @ORM\PreUpdate
     */
    protected function updateAt()
    {
        $this->setUpdateAt(new \DateTime());
    }

    /**
     * @return mixed
     */
    public function getUpdateAt()
    {
        return $this->updateAt;
    }

    /**
     * @param mixed $updateAt
     */
    public function setUpdateAt($updateAt)
    {
        $this->updateAt = $updateAt;
    }
}
