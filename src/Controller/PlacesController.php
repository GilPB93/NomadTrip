<?php

namespace App\Controller;

use App\Entity\Places;
use App\Entity\Travelbook;
use App\Repository\PlacesRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/places', name: 'app_api_places_')]
class PlacesController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $manager,
        private PlacesRepository $placesRepository,
        private SerializerInterface $serializer,
    ){
    }

    // CREATE A PLACE FOR A TRAVELBOOK
    #[Route(name: 'new', methods: ['POST'])]
    #[OA\Post(
        path: "/api/places",
        summary: "Create a new place for a travelbook",
        requestBody: new OA\RequestBody(
            description: "Place object that needs to be added to the travelbook",
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "name", type: "string", example: "Paris"),
                    new OA\Property(property: "address", type: "string", example: "1 rue de Paris"),
                    new OA\Property(property: "visitAt", type: "string", format: "date-time", example: "2021-12-31T23:59:59+00:00"),
                    new OA\Property(property: "travelbook", type: "integer", example: "1"),
                ]
            )
        ),
        tags: ["Places for a travelbook"],
        responses: [
            new OA\Response(
                response: 201,
                description: "Place created",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "id", type: "integer", example: "1"),
                        new OA\Property(property: "name", type: "string", example: "Paris"),
                        new OA\Property(property: "address", type: "string", example: "1 rue de Paris"),
                        new OA\Property(property: "visitAt", type: "string", format: "date-time", example: "2021-12-31T23:59:59+00:00"),
                        new OA\Property(property: "travelbook", type: "integer", example: "1"),
                        ]
                    )
                ),
            new OA\Response(
                response: 400,
                description: "Invalid input",
            )
        ]
    )]
    public function new(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $travelbook = $this->manager->getRepository(Travelbook::class)->find($data['travelbook']);
        if (!$travelbook) {
            return new JsonResponse('Travelbook not found', Response::HTTP_BAD_REQUEST);
        }

        $places = $this->serializer->deserialize($request->getContent(), Places::class, 'json');
        $places->setVisitAt(new DateTimeImmutable($data['visitAt']));
        $places->setTravelbook($travelbook);

        $this->manager->persist($places);
        $this->manager->flush();

        return new JsonResponse(
            $this->serializer->serialize($places, 'json', ['groups' => 'places:read']),
            Response::HTTP_CREATED,
        );
    }

    // SHOW A PLACE FROM A TRAVELBOOK
    #[Route('/{id}', name: 'show', methods: ['GET'])]
    #[OA\Get(
        path: '/api/places/{id}',
        summary: 'Get a place by ID',
        tags: ['Places for a travelbook'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'ID of the place to return',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(
                response: '200',
                description: 'Place found',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'id', type: 'integer', example: 1),
                        new OA\Property(property: 'name', type: 'string', example: 'Paris'),
                        new OA\Property(property: 'address', type: 'string', example: '1 rue de Paris'),
                        new OA\Property(property: 'visitAt', type: 'string', format: 'date-time', example: '2021-12-31T23:59:59+00:00'),
                        new OA\Property(property: 'travelbook', type: 'integer', example: 1),
                    ],
                    type: 'object'
                )
            ),
            new OA\Response(
                response: '404',
                description: 'Place not found'
            )
        ]

    )]
    public function show(int $id): JsonResponse
    {
        $places = $this->placesRepository->findOneBy(['id' => $id]);

        if(!$places) {
            return new JsonResponse(
                'Place not found',
                Response::HTTP_NOT_FOUND,
            );
        }

        return new JsonResponse(
            $this->serializer->serialize($places, 'json', ['groups' => 'places:read']),
            Response::HTTP_OK,
        );
    }

    // UPDATE A PLACE FROM A TRAVELBOOK
    #[Route('/{id}', name: 'edit', methods: ['PUT'])]
    #[OA\Put(
        path: '/api/places/{id}',
        summary: 'Update a place by ID',
        requestBody: new OA\RequestBody(
            description: 'Place object that needs to be updated',
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'Paris'),
                    new OA\Property(property: 'address', type: 'string', example: '1 rue de Paris'),
                    new OA\Property(property: 'visitAt', type: 'string', format: 'date-time', example: '2021-12-31T23:59:59+00:00'),
                    new OA\Property(property: 'travelbook', type: 'integer', example: 1),
                ]
            )
        ),
        tags: ['Places for a travelbook'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'ID of the place to update',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(
                response: '204',
                description: 'Place updated',
            ),
            new OA\Response(
                response: '404',
                description: 'Place not found'
            )
        ]
    )]
    public function edit(int $id, Request $request): JsonResponse
    {
        $places = $this->placesRepository->findOneBy(['id' => $id]);

        if(!$places) {
            return new JsonResponse(
                'Place not found',
                Response::HTTP_NOT_FOUND,
            );
        }

        $this->serializer->deserialize($request->getContent(), Places::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $places]);
        $this->manager->flush();

        return new JsonResponse(
            $this->serializer->serialize($places, 'json', ['groups' => 'places:read']),
            Response::HTTP_OK,
        );
    }


    // DELETE A PLACE FROM A TRAVELBOOK
    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    #[OA\Delete(
        path: '/api/places/{id}',
        summary: 'Delete a place by ID',
        tags: ['Places for a travelbook'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'ID of the place to delete',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(
                response: '200',
                description: 'Place deleted',
            ),
            new OA\Response(
                response: '404',
                description: 'Place not found'
            )
        ]
    )]
    public function delete(int $id): JsonResponse
    {
        $places = $this->placesRepository->findOneBy(['id' => $id]);

        if(!$places) {
            return new JsonResponse(
                'Place not found',
                Response::HTTP_NOT_FOUND,
            );
        }

        $this->manager->remove($places);
        $this->manager->flush();

        return new JsonResponse(
            'Place deleted',
            Response::HTTP_OK,
        );
    }
}
