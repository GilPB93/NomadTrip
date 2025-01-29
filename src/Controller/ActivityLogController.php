<?php

namespace App\Controller;

use App\Entity\ActivityLog;
use App\Entity\User;
use App\Repository\ActivityLogRepository;
use Doctrine\ORM\EntityManagerInterface;
use OpenApi\Attributes as OA;
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
    #[OA\Post(
        path: '/api/activitylog/lastlogin',
        summary: 'Log the last login of the user',
        requestBody: new OA\RequestBody(
            description: 'The last login data',
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'lastLogin', type: 'string', format: 'date-time', example: '2021-09-01T12:00:00+00:00')
                ],
                type: 'object'
            )
        ),
        tags: ['Activity Log'],
        responses: [
            new OA\Response(
                response: '200',
                description: 'Last login updated successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Last login updated successfully')
                    ],
                    type: 'object'
                )
            ),
            new OA\Response(
                response: '404',
                description: 'User not found',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'User not found')
                    ],
                    type: 'object'
                )
            ),
            new OA\Response(
                response: '400',
                description: 'Invalid input data',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Invalid input data')
                    ],
                    type: 'object'
                )
            )
        ]
    )]
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
    #[OA\Post(
        path: '/api/activitylog/incrementconnectiontime',
        summary: 'Increment the total connection time of the user',
        requestBody: new OA\RequestBody(
            description: 'The additional connection time',
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'connectionTime', type: 'integer', example: 10)
                ],
                type: 'object'
            )
        ),
        tags: ['Activity Log'],
        responses: [
            new OA\Response(
                response: '200',
                description: 'Connection time updated successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Connection time updated successfully')
                    ],
                    type: 'object'
                )
            ),
            new OA\Response(
                response: '404',
                description: 'User not found',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'User not found')
                    ],
                    type: 'object'
                )
            )
        ]
    )]
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
    #[OA\Get(
        path: '/api/activitylog/activitylog/{userId}',
        summary: 'Get the activity log of a user',
        tags: ['Activity Log'],
        responses: [
            new OA\Response(
                response: '200',
                description: 'Activity log found',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'lastLogin', type: 'string', format: 'date-time', example: '2021-09-01T12:00:00+00:00'),
                        new OA\Property(property: 'totalConnectionTime', type: 'integer', example: 10)
                    ],
                    type: 'object'
                )
            ),
            new OA\Response(
                response: '404',
                description: 'Activity log not found',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Activity log not found')
                    ],
                    type: 'object'
                )
            )
        ]
    )]
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
