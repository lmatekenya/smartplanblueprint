<?php

namespace App\Repository\HeaderIcons;

use App\Entity\HeaderIcons\Notification;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class NotificationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Notification::class);
    }

    /**
     * Find notifications by user with pagination
     */
    public function findByUser(User $user, array $orderBy = null, $limit = null, $offset = null): array
    {
        return $this->findBy(['user' => $user], $orderBy, $limit, $offset);
    }

    /**
     * Find unread notifications by user
     */
    public function findUnreadByUser(User $user, array $orderBy = null, $limit = null, $offset = null): array
    {
        return $this->findBy([
            'user' => $user,
            'isRead' => false
        ], $orderBy, $limit, $offset);
    }

    /**
     * Count unread notifications for a user
     */
    public function countUnreadByUser(User $user): int
    {
        return $this->count([
            'user' => $user,
            'isRead' => false
        ]);
    }

    /**
     * Mark all notifications as read for a user
     */
    public function markAllAsRead(User $user): void
    {
        $this->createQueryBuilder('n')
            ->update()
            ->set('n.isRead', true)
            ->set('n.readAt', ':now')
            ->where('n.user = :user')
            ->andWhere('n.isRead = false')
            ->setParameter('user', $user)
            ->setParameter('now', new \DateTime())
            ->getQuery()
            ->execute();
    }

    /**
     * Mark a specific notification as read
     */
    public function markAsRead(Notification $notification): void
    {
        $notification->setIsRead(true);
        $notification->setReadAt(new \DateTime());

        $this->_em->persist($notification);
        $this->_em->flush();
    }

    /**
     * Find notifications by type
     */
    public function findByType(User $user, string $type, int $limit = 10): array
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.user = :user')
            ->andWhere('n.type = :type')
            ->setParameter('user', $user)
            ->setParameter('type', $type)
            ->orderBy('n.createdAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}
