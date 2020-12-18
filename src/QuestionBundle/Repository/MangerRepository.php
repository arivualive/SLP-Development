<?php

namespace QuestionBundle\Repository;

/**
 * MangerRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class MangerRepository extends \Doctrine\ORM\EntityRepository
{
    //trouve toutes les questions d'un sondage
    public function findQuestionBySurvey($survey,$DerniereQuestion)

    {
        $entityManager = $this->getEntityManager();    
        $query = $entityManager->createQuery(
            'SELECT q
            FROM QuestionBundle\Entity\Manger q
            WHERE q.survey = :survey
            AND q.numQuestion > :DerniereQuestion 
            ORDER BY q.numQuestion')
            ->setParameters([
                'survey' => $survey,
                'DerniereQuestion' => $DerniereQuestion
            ]);
    
        return $query->execute();
    }

    public function findLastQuestionBySurvey($survey)

    {
        $entityManager = $this->getEntityManager();    
        $query = $entityManager->createQuery(
            'SELECT q
            FROM QuestionBundle\Entity\Manger q
            WHERE q.survey = :survey
            ORDER BY q.numQuestion DESC'
            )
            ->setParameters([
                'survey' => $survey
            ]);
    
        return $query->execute();
    }


    public function getTotalQuestions(){

        $entityManager = $this->getEntityManager();
        $qb = $entityManager->createQueryBuilder();
        $qb->select('count(q.questions)');
        $qb->from('QuestionBundle:Manger','q');

        return $qb->getQuery()->getSingleScalarResult();
    }
}