<?php

namespace App\Controller;

use App\Entity\User;
use App\Enum\AccountStatus;
use App\Repository\ActivityLogRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/user', name: 'app_api_user_')]
class UserController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $manager,
        private SerializerInterface $serializer,
        private ActivityLogRepository $activityLogRepository,
        private UserRepository $userRepository,
    ){
    }

    private function getUserIdFromCookie(Request $request): ?int
    {
        $userId = $request->cookies->get('UserIdCookieName');
        return is_numeric($userId) ? (int)$userId : null;
    }


    // SHOW USER
    #[Route('/{id}', name: 'show', methods: ['GET'])]
    #[OA\Get(
        path: '/api/user/{id}',
        summary: 'Get user by id',
        tags: ['User'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'The user id',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(
                response: '200',
                description: 'User found',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'id', type: 'integer', example: 1),
                        new OA\Property(property: 'email', type: 'string', example: 'example@email.com'),
                        new OA\Property(property: 'firstName', type: 'string', example: 'User first name'),
                        new OA\Property(property: 'lastName', type: 'string', example: 'User last name'),
                        new OA\Property(property: 'pseudo', type: 'string', example: 'User pseudo'),
                    ],
                    type: 'object'
                )
            ),
            new OA\Response(
                response: '404',
                description: 'User not found',
            )
        ]
    )]
    public function show(Request $request): JsonResponse
    {
        $userId = $this->getUserIdFromCookie($request);
        if (!$userId) {
            return new JsonResponse(['error' => 'User ID not found in cookie'], Response::HTTP_UNAUTHORIZED);
        }

        $user = $this->manager->getRepository(User::class)->find($userId);
        if (!$user) {
            return new JsonResponse(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse(
            $this->serializer->serialize($user, 'json'),
            Response::HTTP_OK,
        );
    }


    // EDIT USER
    #[Route('/{id}', name: 'edit', methods: ['PUT'])]
    #[OA\Put(
        path: '/api/user/{id}',
        summary: 'Edit user by id',
        tags: ['User'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'The user id',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(
                response: '200',
                description: 'User edited',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'id', type: 'integer', example: 1),
                        new OA\Property(property: 'email', type: 'string', example: 'example@email.com'),
                        new OA\Property(property: 'firstName', type: 'string', example: 'User first name'),
                        new OA\Property(property: 'lastName', type: 'string', example: 'User last name'),
                        new OA\Property(property: 'pseudo', type: 'string', example: 'User pseudo'),
                    ],
                    type: 'object'
                )
            ),
            new OA\Response(
                response: '404',
                description: 'User not found',
            )
        ]
    )]
    public function edit(Request $request): JsonResponse
    {
        $userId = $this->getUserIdFromCookie($request);
        if (!$userId) {
            return new JsonResponse(['error' => 'User ID not found in cookie'], Response::HTTP_UNAUTHORIZED);
        }

        $user = $this->manager->getRepository(User::class)->find($userId);
        if (!$user) {
            return new JsonResponse(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
        }

        $user->setUpdatedAt(new \DateTimeImmutable());
        $this->serializer->deserialize($request->getContent(), User::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $user]);
        $this->manager->flush();

        return new JsonResponse(
            $this->serializer->serialize($user, 'json'),
            Response::HTTP_OK,
        );
    }


    // DELETE USER
    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    #[OA\Delete(
        path: '/api/user/{id}',
        summary: 'Delete user by id',
        tags: ['User'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'The user id',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(
                response: '200',
                description: 'User deleted',
            ),
            new OA\Response(
                response: '404',
                description: 'User not found',
            )
        ]
    )]
    public function delete(Request $request): JsonResponse
    {
        $userId = $this->getUserIdFromCookie($request);
        if (!$userId) {
            return new JsonResponse(['error' => 'User ID not found in cookie'], Response::HTTP_UNAUTHORIZED);
        }

        $user = $this->manager->getRepository(User::class)->find($userId);
        if (!$user) {
            return new JsonResponse(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
        }
    
        $this->manager->remove($user);
        $this->manager->flush();
    
        return new JsonResponse(
            ['message' => 'User successfully deleted'],
            Response::HTTP_OK
        );
    }


    // GET TOTAL CONNECTION TIME
    #[Route('/{id}/update-total-connection-time', name: 'update_total_connection_time', methods: ['PUT'])]
    #[OA\Put(
        path: '/api/user/{id}/update-total-connection-time',
        summary: 'Update total connection time of user by id',
        tags: ['User'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'The user id',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(
                response: '200',
                description: 'Total connection time updated',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Total connection time updated successfully'),
                        new OA\Property(property: 'totalConnectionTime', type: 'integer', example: 3600),
                    ],
                    type: 'object'
                )
            ),
            new OA\Response(
                response: '404',
                description: 'User not found',
            )
        ]
    )]
    public function updateTotalConnectionTime(Request $request): JsonResponse
    {
        $user = $this->manager->getRepository(User::class)->findOneBy(['id' => $request->get('id')]);
        if (!$user) {
            return new JsonResponse(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
        }

        $totalConnectionTime = $this->activityLogRepository->createQueryBuilder('a')
            ->select('SUM(a.durationOfConnection)')
            ->where('a.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getSingleScalarResult();

        $user->setTotalConnectionTime($totalConnectionTime ?? 0);
        $this->manager->persist($user);
        $this->manager->flush();


        return new JsonResponse([
            'message' => 'Total connection time updated successfully',
            'totalConnectionTime' => $user->getTotalConnectionTime(),
        ]);
    }


    // GET LIST OF USERS
    #[Route('/all', name: 'list', methods: ['GET'])]
    #[isGranted('ROLE_ADMIN')]
    #[OA\Get(
        path: "/api/user/all",
        summary: "Récupérer la liste des utilisateurs",
        tags: ["Utilisateur"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Liste des utilisateurs récupérée avec succès",
                content: new OA\JsonContent(type: "array", items: new OA\Items(ref: "#/components/schemas/User"))
            )
        ]
    )]
    public function getUsers(): JsonResponse
    {
        $users = $this->userRepository->findAll();
        return new JsonResponse($this->serializer->serialize($users, 'json', ['groups' => 'user:read']), 200, [], true);
    }

    // ACTIVATE/DEACTIVATE USER
    #[Route('/toggle-status/{id}', name: 'toggle_status', methods: ['PATCH'])]
    #[isGranted('ROLE_ADMIN')]
    #[OA\Patch(
        path: "/api/user/toggle-status/{id}",
        summary: "Activate or deactivate a user",
        tags: ["Utilisateur"],
        parameters: [
            new OA\Parameter(
                name: "id",
                description: "User ID",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(response: 200, description: "Status updated successfully"),
            new OA\Response(response: 404, description: "User not found")
        ]
    )]
    public function toggleUserStatus(int $id): JsonResponse
    {
        $user = $this->userRepository->find($id);
        if (!$user) {
            return new JsonResponse(['message' => 'User not found'], 404);
        }

        $user->setAccountStatus(
            $user->getAccountStatus() === AccountStatus::ACTIVE ? AccountStatus::INACTIVE : AccountStatus::ACTIVE
        );

        $this->manager->flush();
        return new JsonResponse(['message' => 'Status updated successfully'], 200);
    }

    // GET USER DETAILS
    #[Route('/details/{id}', name: 'details', methods: ['GET'])]
    #[isGranted('ROLE_ADMIN')]
    #[OA\Get(
        path: "/api/user/details/{id}",
        summary: "Get details of a user",
        tags: ["Utilisateur"],
        parameters: [
            new OA\Parameter(
                name: "id",
                description: "User ID",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "User details retrieved successfully",
                content: new OA\JsonContent(
                    properties: [
                    new OA\Property(property: "id", type: "integer", example: 1),
                    new OA\Property(property: "email", type: "string", example: "user@example.com"),
                    new OA\Property(property: "firstName", type: "string", example: "John"),
                    new OA\Property(property: "lastName", type: "string", example: "Doe"),
                    new OA\Property(property: "pseudo", type: "string", example: "johndoe"),
                    new OA\Property(property: "accountStatus", type: "string", example: "Actif"),
                    new OA\Property(property: "createdAt", type: "string", format: "date-time", example: "2024-01-01T12:00:00Z"),
                    new OA\Property(property: "numberOfTravelbooks", type: "integer", example: 5)
                    ],
                ),
            ),
            new OA\Response(response: 404, description: "User not found")
        ]
    )]
    public function getUserDetails(int $id): JsonResponse
    {
        $user = $this->userRepository->find($id);
        if (!$user) {
            return new JsonResponse(['message' => 'User not found'], 404);
        }

        return new JsonResponse($this->serializer->serialize($user, 'json', ['groups' => 'user:read']), 200, [], true);
    }



}
