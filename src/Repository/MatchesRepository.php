<?php

namespace App\Repository;

use App\Entity\Matches;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Matches>
 */
class MatchesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Matches::class);
    }

    /**
     * @return list<Matches>
     */
    public function findAllRecentFirst(): array
    {
        return $this->createQueryBuilder('m')
            ->orderBy('m.date', 'DESC')
            ->addOrderBy('m.id', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Identifiants d'équipes présents au moins une fois comme domicile ou extérieur.
     *
     * @return list<int>
     */
    public function findReferencedTeamIds(): array
    {
        $homeRows = $this->createQueryBuilder('m')
            ->select('IDENTITY(m.homeTeam) AS id')
            ->getQuery()
            ->getScalarResult();

        $awayRows = $this->createQueryBuilder('m')
            ->select('IDENTITY(m.awayTeam) AS id')
            ->getQuery()
            ->getScalarResult();

        $ids = array_merge(
            array_column($homeRows, 'id'),
            array_column($awayRows, 'id'),
        );

        $ids = array_filter($ids, static fn ($id) => null !== $id && '' !== $id);

        return array_values(array_unique(array_map(static fn ($id): int => (int) $id, $ids)));
    }

//    /**
//     * @return Matches[] Returns an array of Matches objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Matches
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
