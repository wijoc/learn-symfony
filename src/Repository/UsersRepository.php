<?php

namespace App\Repository;

use App\Entity\Users;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Users>
 */
// class UsersRepository extends ServiceEntityRepository
class UsersRepository extends MyRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Users::class);
    }

    //    /**
    //     * @return Users[] Returns an array of Users objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('u.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Users
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function scopeSearch(QueryBuilder $query, array $filters): QueryBuilder
    {
        if (isset($filters['s']) && !empty($filters['s']) && $filters['s'] !== "") {
            $query->andWhere(
                $query->expr()->orX(
                    $query->expr()->like('u.username', ':keyword'),
                    $query->expr()->like('u.email', ':keyword'),
                    $query->expr()->like('u.first_name', ':keyword'),
                    $query->expr()->like('u.last_name', ':keyword'),
                )
            )->setParameter('keyword', '%' . $filters['s'] . '%');
        }

        return $query;
    }

    /**
     * Get users on filters function
     *
     * @param array $filters
     * @return array
     */
    public function getUsers(array $filters = []): array
    {
        $query = $this->createQueryBuilder('u');
        $query = $this->search($query, $filters);

        $results = $query->getQuery()->getResult();
        return $results;
    }
}
