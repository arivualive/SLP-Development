<?php

namespace QuestionBundle\Repository;

use QuestionBundle\QuestionBundle;
use Symfony\Bundle\FrameworkBundle\Console\Descriptor\Descriptor;

/**
 * AnswerRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class AnswerRepository extends \Doctrine\ORM\EntityRepository
{

    //get user/question
    public function findByUserSlp($userSlp, $survey) {
        $qb = $this->_em
            ->createQueryBuilder()
            ->select('a')
            ->from('QuestionBundle:Answer', 'a')
            ->join('a.question', 'q')
            ->where('a.userSlp = :userSlp and q.survey = :survey')
            ->setParameters(['userSlp' => $userSlp, 'survey' => $survey]);
        return $qb->getQuery()->getResult();
    }
     public function findOneByUserSlpAndQuestion($userSlp,$question) {
        $qb = $this->_em
            ->createQueryBuilder()
            ->select('a')
            ->from('QuestionBundle:Answer', 'a')
            ->where('a.userSlp = :userSlp and a.question = :question')
            ->setParameters(['userSlp' => $userSlp, 'question' => $question]);
        return $qb->getQuery()->getResult(); 
        /* $query = $this->getEntityManager()
        ->createQuery(
            'SELECT * FROM QuestionBundle:Answer a
            WHERE a.userSlp = :userSlp'
        )->setParameter('userSlp', $userSlp);
        return $query->getResult(); */        
    }

    //Récupérer les questions ou les dates sont supérieur a la réponse
    public function findByDateQuestionAndAnswer($userSlp){
        $qb = $this->_em
            ->createQueryBuilder()
            ->select('a,q')
            ->from('QuestionBundle:Answer','a')
            ->innerJoin('a.question','q')
            ->where('q.updateAt > a.updateAt')
            ->andWhere('a.userSlp = :userSlp')
            ->orderBy('q.number')
            ->setParameter('userSlp',$userSlp);
            
        return $qb->getQuery()->getResult();
    }

    //Récupère la date de réponse la plus grande
    public function findByLatestAnsweredDate($userSlp)
    {
        $qb = $this->_em
            ->createQueryBuilder()
            ->Select('max(a.updateAt)')
            ->from('QuestionBundle:Answer','a')
            ->where('a.updateAt > :updateAt')
            //->groupBy('a')
            ->orderBy('a.updateAt')
            ->setParameter('updateAt',$userSlp);

        return $qb->getQuery()->getResult(); 
        //SELECT *, MAX(update_at) FROM answer GROUP BY id ORDER BY MAX(update_at) DESC
        //SELECT MAX(update_at) FROM answer
        //SELECT *, MAX(update_at) FROM answer WHERE update_at IS NOT NULL GROUP BY id;
    }

}