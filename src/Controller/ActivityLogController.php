<?php

namespace App\Controller;

use App\Entity\ActivityLog;
use App\Entity\User;
use App\Repository\ActivityLogRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/activitylog', name: 'app_api_activitylog_')]
class ActivityLogController extends AbstractController
{
    public function __construct(private EntityManagerInterface $manager)
    {
    }

    //LAST LOGIN
    #[Route('/lastlogin', name: 'lastlogin', methods: ['POST'])]
    public function logLastLogin(): JsonResponse
    {
        $user = $this->getUser();
        if (!$user instanceof User) {
            return new JsonResponse(['message' => 'User not found'], 404);
        }

        $activityLog = $this->manager
            ->getRepository(ActivityLog::class)
            ->findOneBy(['user' => $user]);

        if (!$activityLog) {
            $activityLog = new ActivityLog();
            $activityLog->setUser($user);
        }

        $activityLog->setLastLogin(new \DateTimeImmutable());

        $this->manager->persist($activityLog);
        $this->manager->flush();

        return new JsonResponse(['message' => 'Last login updated successfully']);
    }

    //INCREMENT CONNECTION TIME
    #[Route('/incrementconnectiontime', name: 'increment_connection_time', methods: ['POST'])]
    public function incrementConnectionTime(Request $request): JsonResponse
    {
        $user = $this->getUser(); // Récupère l'utilisateur connecté
        if (!$user instanceof User) {
            return new JsonResponse(['message' => 'User not found'], 404);
        }

        $activityLog = $this->manager
            ->getRepository(ActivityLog::class)
            ->findOneBy(['user' => $user]);

        if (!$activityLog) {
            return new JsonResponse(['message' => 'Activity log not found'], 404);
        }

        $data = json_decode($request->getContent(), true);
        $additionalTime = $data['connectionTime'] ?? 0;

        $activityLog->setTotalConnectionTime(
            $activityLog->getTotalConnectionTime() + $additionalTime
        );

        $this->manager->flush();

        return new JsonResponse(['message' => 'Connection time updated successfully']);
    }

    //USER ACTIVITY LOG
    #[Route('/activitylog/{userId}', name: 'user_activity', methods: ['GET'])]
    public function getUserActivity(int $userId, ActivityLogRepository $activityLogRepository): JsonResponse
    {
        $activityLog = $activityLogRepository->findOneBy(['user' => $userId]);

        if (!$activityLog) {
            return new JsonResponse(['message' => 'Activity log not found'], 404);
        }

        return new JsonResponse([
            'lastLogin' => $activityLog->getLastLogin(),
            'totalConnectionTime' => $activityLog->getTotalConnectionTime()
        ]);
    }


}
