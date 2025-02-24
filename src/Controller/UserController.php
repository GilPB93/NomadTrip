<?php

namespace App\Controller;

use App\Entity\User;
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

    // UPDATE CONNECTION TIME


    // GET TOTAL OF USERS
    #[Route('/userscount', name: 'users_count', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    #[OA\Get(
        path: '/api/user/userscount',
        summary: 'Get the total number of users',
        tags: ['User'],
        responses: [
            new OA\Response(
                response: '200',
                description: 'The total number of users',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'totalUsers', type: 'integer', example: 10),
                    ],
                    type: 'object'
                )
            ),
            new OA\Response(
                response: '401',
                description: 'Unauthorized access',
            )
        ]
    )]
    public function getUsersCount(): JsonResponse
    {
        $totalUsers = $this->userRepository->count([]);

        return new JsonResponse(
            ['totalUsers' => $totalUsers],
            Response::HTTP_OK
        );
    }



}
