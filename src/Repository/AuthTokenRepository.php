<?php

namespace App\Repository;

use App\Entity\AuthToken;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AuthToken>
 */
class AuthTokenRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AuthToken::class);
    }

    //    /**
    //     * @return AuthToken[] Returns an array of AuthToken objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('a.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    public function findValidByHash(string $hash): ?AuthToken
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.hash = :hash')
            ->andWhere('a.expiresAt > :now')
            ->setParameter('hash', $hash)
            ->setParameter('now', new \DateTimeImmutable())
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
