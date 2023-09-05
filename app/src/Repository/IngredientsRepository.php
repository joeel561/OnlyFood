<?php

namespace App\Repository;

use App\Entity\Ingredients;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Recipe;

/**
 * @extends ServiceEntityRepository<Ingredients>
 *
 * @method Ingredients|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ingredients|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ingredients[]    findAll()
 * @method Ingredients[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IngredientsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ingredients::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Ingredients $entity, bool $flush = true): void
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
    public function remove(Ingredients $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function getRecipeId(Recipe $recipe)
    {
        return $this->createQueryBuilder('i')
            ->where(':recipeId MEMBER OF i.recipes')
            ->setParameters(array('recipeId' => $recipe))
            ->getQuery()
            ->getResult()
        ;
    }

    public function getFilterIngredients() 
    {
        return $this->createQueryBuilder('i')
            ->select('i.name')
            ->groupBy('i.name')
            ->getQuery()
            ->getResult()
        ;
    }

    public function getNotInIngredientsList($ingredients)
    {
        $query = $this->createQueryBuilder('i');
        $query->select('i')
            ->innerJoin('i.recipes', 'r')
            ->where('i.name NOT IN (:ingredients)')
            ->setParameter('ingredients', $ingredients, \Doctrine\DBAL\Connection::PARAM_STR_ARRAY);

        return $query->getQuery()->getResult();
    }

    // /**
    //  * @return Ingredients[] Returns an array of Ingredients objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Ingredients
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
