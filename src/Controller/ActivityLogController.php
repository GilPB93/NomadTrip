<?php

namespace App\Controller;

use App\Entity\ActivityLog;
use App\Repository\ActivityLogRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;

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
    #[OA\Post(
        path: '/api/activity-log/set-login-time',
        summary: 'Set login time',
        requestBody: new OA\RequestBody(
            description: 'No request body required, user session is used'
        ),
        tags: ['Activity Log'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Login time recorded successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Login time recorded successfully'),
                        new OA\Property(property: 'login_time', type: 'string', format: 'date-time', example: '2025-02-25T10:55:57.000Z')
                    ],
                    type: 'object'
                )
            ),
            new OA\Response(response: 403, description: 'User not authenticated'),
        ]
    )]
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
    #[OA\Post(
        path: '/api/activity-log/set-logout-time',
        summary: 'Set logout time',
        requestBody: new OA\RequestBody(
            description: 'Send the login time to calculate the session duration',
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'login_time', type: 'string', format: 'date-time', example: '2025-02-25T10:55:57.000Z')
                ],
                type: 'object'
            )
        ),
        tags: ['Activity Log'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Logout time recorded successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Logout time recorded successfully'),
                        new OA\Property(property: 'logout_time', type: 'string', format: 'date-time', example: '2025-02-25T11:25:57.000Z'),
                        new OA\Property(property: 'duration', type: 'integer', example: 1800)
                    ],
                    type: 'object'
                )
            ),
            new OA\Response(response: 400, description: 'Missing login_time'),
            new OA\Response(response: 403, description: 'User not authenticated'),
            new OA\Response(response: 404, description: 'No matching login time found'),
        ]
    )]
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


    #[Route('/purge-logout-null', name: 'activity_log_purge_logout_null', methods: ['DELETE'])]
    #[OA\Delete(
        path: '/api/activity-log/purge-logout-null',
        summary: 'Purge activity logs with null logout',
        tags: ['Activity Log'],
        responses: [
            new OA\Response(response: 200, description: 'Logs purged successfully'),
            new OA\Response(response: 403, description: 'Access denied'),
        ]
    )]
    public function purgeLogsWithNullLogout(): JsonResponse
    {

        $query = $this->entityManager->createQuery(
            'DELETE FROM App\Entity\ActivityLog a WHERE a.logout IS NULL'
        );

        $deletedRows = $query->execute();

        return new JsonResponse([
            'message' => 'Purge completed successfully',
            'deleted_rows' => $deletedRows
        ]);
    }

}
