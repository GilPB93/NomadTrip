<?php

namespace App\Controller;

use App\Entity\User;
use App\Enum\AccountStatus;
use Doctrine\ORM\EntityManagerInterface;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api', name: 'app_api_')]
class SecurityController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $manager,
        private SerializerInterface $serializer
    ){
    }

    //REGISTER
    #[Route('/register', name: 'register', methods: ['POST'])]
    #[OA\Post(
        path: '/api/register',
        summary: 'Register a new user',
        requestBody: new OA\RequestBody(
            description: 'The user data',
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'email', type: 'string', example: 'example@email.com'),
                    new OA\Property(property: 'password', type: 'string', example: 'Example@123'),
                    new OA\Property(property: 'firstName', type: 'string', example: 'User first name'),
                    new OA\Property(property: 'lastName', type: 'string', example: 'User last name'),
                    new OA\Property(property: 'pseudo', type: 'string', example: 'User pseudo'),
                ],
                type: 'object'
            )
        ),
        tags: ['User'],
        responses: [
            new OA\Response(
                response: '201',
                description: 'User created successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'user', type: 'string', example: 'User name'),
                        new OA\Property(property: 'apiToken', type: 'string', example: '31a023e212f116124a36af14ea0c1c3806eb9378'),
                        new OA\Property(property: 'roles', type: 'array', items: new OA\Items(type: 'string', example: 'ROLE_USER')),
                    ],
                    type: 'object'
                )
            )
        ]
    )]
    public function register (Request $request, UserPasswordHasherInterface $passwordHasher) : JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $existingUser = $this->manager->getRepository(User::class)->findOneBy(['email' => $data['email']]);
        if ($existingUser) {
            return new JsonResponse(
                ['error' => 'Email already exists'],
                Response::HTTP_CONFLICT
            );
        }

        $user = $this->serializer->deserialize($request->getContent(), User::class, 'json');
        $user->setPassword($passwordHasher->hashPassword($user, $user->getPassword()));
        $user->setRoles(['ROLE_USER']);
        $user->setAccountStatus(AccountStatus::ACTIVE);
        $user->setCreatedAt(new \DateTimeImmutable());
        $user->setUpdatedAt(new \DateTimeImmutable());

        $this->manager->persist($user);
        $this->manager->flush();

        return new JsonResponse(
            ['user' => $user->getUserIdentifier(), 'apiToken' => $user->getApiToken(), 'roles' => $user->getRoles()],
            Response::HTTP_CREATED,
            ['Access-Control-Allow-Origin' => 'https://nomadtripfrontend-934f654ec662.herokuapp.com']
        );
    }

    //LOGIN
    #[Route('/login', name: 'login', methods: ['POST'])]
    #[OA\Post(
        path: '/api/login',
        summary: 'Login a user',
        requestBody: new OA\RequestBody(
            description: 'The user data',
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'email', type: 'string', example: 'example@email.com'),
                    new OA\Property(property: 'password', type: 'string', example: 'Example@123'),
                ],
                type: 'object'
            )
        ),
        tags: ['User'],
        responses: [
            new OA\Response(
                response: '200',
                description: 'User logged in successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'user', type: 'string', example: 'User name'),
                        new OA\Property(property: 'id', type: 'integer', example: 1),
                        new OA\Property(property: 'apiToken', type: 'string', example: '31a023e212f116124a36af14ea0c1c3806eb9378'),
                        new OA\Property(property: 'roles', type: 'array', items: new OA\Items(type: 'string', example: 'ROLE_USER')
                        )
                    ],
                    type: 'object'
                )
            ),
            new OA\Response(
                response: '401',
                description: 'Invalid credentials'
            )
        ]
    )]
    public function login (#[CurrentUser] ?User $user) : JsonResponse
    {
        if(null === $user) {
            return new JsonResponse(
                ['message' => 'Invalid credentials'],
                Response::HTTP_UNAUTHORIZED,
                ['Access-Control-Allow-Origin' => 'https://nomadtripfrontend-934f654ec662.herokuapp.com']
            );
        }

        return new JsonResponse([
            'user' => $user->getUserIdentifier(),
            'id' => $user->getId(),
            'apiToken' => $user->getApiToken(),
            'roles' => $user->getRoles()
        ]);
    }

    // MY USER INFORMATION
    #[Route('/accountInfo', name: 'accountInfo', methods: ['GET'])]
    #[OA\Get(
        path: '/api/accountInfo',
        summary: 'Get user information',
        tags: ['User'],
        responses: [
            new OA\Response(
                response: '200',
                description: 'User information',
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
    public function accountInfo(): JsonResponse
    {
        $user = $this->getUser();

        if (!$user) {
            return new JsonResponse(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
        }

        $responseData = $this->serializer->serialize(
            $user,
            'json',
            [AbstractNormalizer::GROUPS => ['user:read']]
        );

        return new JsonResponse($responseData,
            Response::HTTP_OK,
            ['Access-Control-Allow-Origin' => 'https://nomadtripfrontend-934f654ec662.herokuapp.com'],
            true);
    }
}
