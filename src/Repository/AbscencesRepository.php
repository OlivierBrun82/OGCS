<?php

namespace App\Repository;

use App\Entity\Abscences;
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
     * Toutes les absences, visibles par tout coach connecté (évite les requêtes N+1 sur user / joueur).
     *
     * @return list<Abscences>
     */
    public function findAllOrderedForListing(): array
    {
        return $this->createQueryBuilder('a')
            ->leftJoin('a.user', 'u')->addSelect('u')
            ->leftJoin('a.players', 'p')->addSelect('p')
            ->orderBy('a.absence_start', 'DESC')
            ->addOrderBy('a.id', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
