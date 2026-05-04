<?php

namespace App\Repository;

use App\Entity\Abscences;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Abscences>
 */
class AbscencesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Abscences::class);
    }

    /**
     * @return list<Abscences>
     */
    public function findForCoach(User $coach): array
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.user = :coach')
            ->setParameter('coach', $coach)
            ->orderBy('a.absence_start', 'DESC')
            ->addOrderBy('a.id', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
