<?php

namespace App\Repository;

use App\Entity\Teams;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Teams>
 */
class TeamsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Teams::class);
    }

    /**
     * Identifiants d'équipes qui ont au moins un joueur rattaché.
     *
     * @return list<int>
     */
    public function findIdsWithAtLeastOnePlayer(): array
    {
        $rows = $this->createQueryBuilder('t')
            ->select('t.id')
            ->innerJoin('t.players', 'p')
            ->distinct()
            ->getQuery()
            ->getScalarResult();

        return array_values(array_unique(array_map(static fn (array $row): int => (int) $row['id'], $rows)));
    }

    //    /**
    //     * @return Teams[] Returns an array of Teams objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('t.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Teams
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
