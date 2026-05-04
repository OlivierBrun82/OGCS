<?php

namespace App\Repository;

use App\Entity\Message;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Message>
 */
class MessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Message::class);
    }

    /**
     * @return list<Message>
     */
    public function findInboxForUser(User $user): array
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.recipient = :u')
            ->setParameter('u', $user)
            ->orderBy('m.sent_at', 'DESC')
            ->addOrderBy('m.id', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return list<Message>
     */
    public function findSentByUser(User $user): array
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.sender = :u')
            ->setParameter('u', $user)
            ->orderBy('m.sent_at', 'DESC')
            ->addOrderBy('m.id', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
