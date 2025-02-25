<?php

namespace App\Controller;

use App\Entity\ActivityLog;
use App\Repository\ActivityLogRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/activity-log', name: 'app_api_activity_log')]
class ActivityLogController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private ActivityLogRepository $activityLogRepository
    ) {
    }

    // SET LOGIN ACTIVITY
    #[Route('/set-login-time', name: 'activity_log_set_login_time', methods: ['POST'])]
    public function setLoginTime(): JsonResponse
    {
        $user = $this->getUser();
        if (!$user) {
            return new JsonResponse(['error' => 'User not authenticated'], 403);
        }

        $activityLog = new ActivityLog();
        $activityLog->setUser($user);
        $activityLog->setLogin(new \DateTimeImmutable());

        $this->entityManager->persist($activityLog);
        $this->entityManager->flush();

        return new JsonResponse([
            'message' => 'Login time recorded successfully',
            'login_time' => $activityLog->getLogin()->format('Y-m-d H:i:s')
        ]);
    }


    // SET LOGOUT ACTIVITY
    #[Route('/set-logout-time', name: 'activity_log_set_logout_time', methods: ['POST'])]
    public function setLogoutTime(Request $request): JsonResponse
    {
        $user = $this->getUser();
        if (!$user) {
            return new JsonResponse(['error' => 'User not authenticated'], 403);
        }

        $data = json_decode($request->getContent(), true);
        if (!isset($data['login_time'])) {
            return new JsonResponse(['error' => 'Missing login_time'], 400);
        }

        $loginTime = new \DateTimeImmutable($data['login_time']);
        $logoutTime = new \DateTimeImmutable();
        $duration = $logoutTime->getTimestamp() - $loginTime->getTimestamp();

        $activityLog = $this->activityLogRepository->findOneBy(
            ['user' => $user, 'login' => $loginTime]
        );

        if (!$activityLog) {
            return new JsonResponse(['error' => 'No matching login time found'], 404);
        }

        $activityLog->setLogout($logoutTime);
        $activityLog->setDurationOfConnection($duration);

        $this->entityManager->flush();

        return new JsonResponse([
            'message' => 'Logout time recorded successfully',
            'logout_time' => $logoutTime->format('Y-m-d H:i:s'),
            'duration' => $duration
        ]);
    }
}
