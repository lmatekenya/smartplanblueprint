<?php

namespace App\Controller\HeaderIcons;


use App\Entity\HeaderIcons\Notification;
use App\Repository\HeaderIcons\NotificationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class NotificationController extends AbstractController
{
    /**
     * @Route("/api/notifications", name="api_notifications", methods={"GET"})
     */

    #[Route('/api/notifications', name: 'api_notifications', methods: ['GET'])]
    public function getNotifications(Request $request, NotificationRepository $notificationRepo): JsonResponse
    {
        $user = $this->getUser();
        $page = $request->query->get('page', 1);
        $limit = $request->query->get('limit', 10);

        $notifications = $notificationRepo->findByUser(
            $user,
            ['createdAt' => 'DESC'],
            $limit,
            ($page - 1) * $limit
        );

        $unreadCount = $notificationRepo->count([
            'user' => $user,
            'isRead' => false
        ]);

        return $this->json([
            'notifications' => $notifications,
            'unreadCount' => $unreadCount
        ]);
    }

    /**
     * @Route("/api/notifications/{id}/read", name="api_notification_read", methods={"POST"})
     */

    #[Route('/api/notifications/{id}/read', name: 'api_notification_read', methods: ['POST'])]
    public function markAsRead(Notification $notification): JsonResponse
    {
        $user = $this->getUser();

        if ($notification->getUser()->getId() !== $user->getId()) {
            return $this->json(['error' => 'Access denied'], 403);
        }

        $notification->setIsRead(true);
        $notification->setReadAt(new \DateTime());

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($notification);
        $entityManager->flush();

        return $this->json(['success' => true]);
    }

    /**
     * @Route("/api/notifications/read-all", name="api_notifications_read_all", methods={"POST"})
     */
    #[Route('/api/notifications/read-all', name: 'api_notifications_read_all', methods: ['POST'])]
    public function markAllAsRead(NotificationRepository $notificationRepo): JsonResponse
    {
        $user = $this->getUser();
        $notificationRepo->markAllAsRead($user);

        return $this->json(['success' => true]);
    }
}
