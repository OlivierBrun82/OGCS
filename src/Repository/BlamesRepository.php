<?php

namespace App\Repository;

use App\Entity\Blames;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Blames>
 */
class BlamesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Blames::class);
    }

    /**
     * @return list<Blames>
     */
    public function findAllRecentFirst(): array
    {
        return $this->createQueryBuilder('b')
            ->orderBy('b.start_date', 'DESC')
            ->addOrderBy('b.id', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
