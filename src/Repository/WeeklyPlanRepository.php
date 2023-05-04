<?php

namespace App\Repository;

use App\Entity\WeeklyPlan;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\User;
use App\Entity\Recipe;

/**
 * @extends ServiceEntityRepository<WeeklyPlan>
 *
 * @method WeeklyPlan|null find($id, $lockMode = null, $lockVersion = null)
 * @method WeeklyPlan|null findOneBy(array $criteria, array $orderBy = null)
 * @method WeeklyPlan[]    findAll()
 * @method WeeklyPlan[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WeeklyPlanRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WeeklyPlan::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(WeeklyPlan $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(WeeklyPlan $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function findWeeklyPlanOfUser(User $user): array
    {
        return $this->createQueryBuilder('w')
            ->where('w.user = :userId')
            ->setParameters(array('userId' => $user))
            ->getQuery()
            ->getResult()
        ;
    }

    public function getTodaysMealPlan(User $user, int $date): array
    {
        return $this->createQueryBuilder('w')
            ->where('w.user = :userId')
            ->andWhere('w.weekDaySort = :date')
            ->setParameters(array('date' => $date, 'userId' => $user))
            ->setMaxResults(4)
            ->getQuery()
            ->getResult()
        ;
    }

    // /**
    //  * @return WeeklyPlan[] Returns an array of WeeklyPlan objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('w.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?WeeklyPlan
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
