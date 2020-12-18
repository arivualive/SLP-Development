<?php

namespace AppBundle\Repository;

/**
 * OtherRevenueRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class OtherRevenueRepository extends \Doctrine\ORM\EntityRepository
{
    public function findAll()
    {
        return $this->findBy(array(), array('date' => 'DESC'));
    }

    public function findByBudget($budgetId)
    {
        return $this->createQueryBuilder('o')
            ->select('o')
            ->from( 'AppBundle\Entity\OtherRevenue',  'o' )
            ->where('o.budget_id = :budgetId')
            ->setParameter('budgetId', $budgetId)
            ->orderBy('o.date', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findOneByBudget($budgetId)
    {
        return $this->createQueryBuilder('o')
            ->select('o')
            //->from( 'AppBundle\Entity\OtherRevenue',  'o' )
            ->where('o.budget = :budgetId')
            ->setParameter('budgetId', $budgetId)
            ->orderBy('o.date', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
        ;
    }

    public function findByBudgetAndDates($budgetId, $minDate, $maxDate)
    {
        return $this->createQueryBuilder('o')
            ->select('o')
            //->from( 'AppBundle\Entity\OtherRevenue',  'o' )
            ->where('o.budget = :budgetId')
            ->andWhere('o.date >= :minDate')
            ->andWhere('o.date <= :maxDate')
            ->setParameter('budgetId', $budgetId)
            ->setParameter('minDate', $minDate)
            ->setParameter('maxDate', $maxDate)
            ->setMaxResults('1')
            ->getQuery()
            ->getResult();
    }

    public function findPreviousForDate($budgetId, $date) {
        return $this->createQueryBuilder('o')
            ->select('o')
            //->from( 'AppBundle\Entity\OtherRevenue',  'o' )
            ->where('o.budget = :budgetId')
            ->andWhere('o.date <= :date')
            ->setParameter('budgetId', $budgetId)
            ->setParameter('date', $date)
            ->orderBy('o.date', 'DESC')
            ->setMaxResults('1')
            ->getQuery()
            ->getOneOrNullResult();
    }

    /*
    public function findOtherRevenuesInSameYear($budgetId)
    {
        return $this->createQueryBuilder('o')
            ->select("SUM(o.amount) as a, o.date")
            ->where("o.budget = :budgetId")
            ->setParameter("budgetId" , $budgetId)
            ->groupBy("o.date")
            ->orderBy("o.date", "DESC")
            ->getQuery()
            ->getResult()
            // ->getSingleScalarResult()
        ;
    }
    */
}