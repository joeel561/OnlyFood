<?php

namespace App\Repository;

use App\Entity\Recipe;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Recipe>
 *
 * @method Recipe|null find($id, $lockMode = null, $lockVersion = null)
 * @method Recipe|null findOneBy(array $criteria, array $orderBy = null)
 * @method Recipe[]    findAll()
 * @method Recipe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecipeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recipe::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Recipe $entity, bool $flush = true): void
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
    public function remove(Recipe $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function getAllTags(Recipe $recipe): array
    {
        return $this->createQueryBuilder('r')
            ->where(':recipeId MEMBER OF r.id')
            ->setParameters(array('recipeId' => $recipe))
            ->getQuery()
            ->getResult()
        ;
    }
    
    public function getLikedRecipe(User $user, Recipe $recipe): array
    {
        return $this->createQueryBuilder('r')
            ->where(':user MEMBER OF r.likedUsers')
            ->andWhere(':recipe = r.id')
            ->setParameters(array('user' => $user, 'recipe' => $recipe))
            ->getQuery()
            ->getResult()
        ;
    }

    public function getSearchResult(string $search): array
    {
        return $this->createQueryBuilder('r')
            ->where('r.name LIKE :search')
            ->innerJoin('r.userId', 'u')
            ->orWhere('u.username LIKE :search')
            ->andWhere('u.publicMode = 1')
            ->setParameters(array('search' => '%' . $search . '%'))
            ->getQuery()
            ->getResult()
        ;
    }

    public function getRecipes(int $offset, User $user, array $tagIds = [], array $ingredientIds = []): array
    {
        $parameters = [
            'user' => $user
        ];

        $qb = $this->createQueryBuilder('r')
            ->where('r.userId = :user')
            ->orWhere(':user MEMBER OF r.likedUsers')
            ->innerJoin('r.userId', 'u')
            ->andWhere('u.publicMode = 1')
            ->setFirstResult($offset)
            ->setMaxResults(9) 
        ;

        if (count($tagIds) > 0) {
            $parameters['tagIds'] = $tagIds;

            $qb->innerJoin('r.tags', 't');
            $qb->andWhere('t.id IN (:tagIds)');
        }

        $qb->setParameters($parameters);

        return $qb->getQuery()
        ->getResult();
    }

    // /**
    //  * @return Recipe[] Returns an array of Recipe objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Recipe
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
