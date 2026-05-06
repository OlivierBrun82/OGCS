<?php

namespace App\Repository;

use App\Entity\Players;
use App\Entity\Ratings;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Ratings>
 */
class RatingsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ratings::class);
    }

    /**
     * @return list<Ratings>
     */
    public function findAllRecentFirst(): array
    {
        return $this->createQueryBuilder('r')
            ->orderBy('r.created_at', 'DESC')
            ->addOrderBy('r.id', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findOneByCoachAndPlayer(User $coach, Players $player): ?Ratings
    {
        return $this->findOneBy([
            'coach' => $coach,
            'player' => $player,
        ]);
    }

    /**
     * Notes du coach pour une liste de joueurs (une requête), clé = id joueur, valeur = note 1–10.
     *
     * @param list<int> $playerIds
     *
     * @return array<int, int>
     */
    public function mapRatingValueByPlayerIdForCoach(User $coach, array $playerIds): array
    {
        if ($playerIds === []) {
            return [];
        }

        $rows = $this->createQueryBuilder('r')
            ->select('IDENTITY(r.player) AS pid', 'r.rating AS rating')
            ->andWhere('r.coach = :coach')
            ->andWhere('r.player IN (:pids)')
            ->setParameter('coach', $coach)
            ->setParameter('pids', $playerIds)
            ->getQuery()
            ->getScalarResult();

        $map = [];
        foreach ($rows as $row) {
            if (null === $row['pid'] || '' === $row['pid']) {
                continue;
            }
            $map[(int) $row['pid']] = (int) $row['rating'];
        }

        return $map;
    }

    //    /**
    //     * @return Ratings[] Returns an array of Ratings objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('r.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Ratings
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
