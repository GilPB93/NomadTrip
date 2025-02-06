<?php

namespace App\Controller;

use App\Entity\Souvenirs;
use App\Entity\Travelbook;
use App\Repository\SouvenirsRepository;
use Doctrine\ORM\EntityManagerInterface;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/souvenirs', name: 'app_api_souvenirs_')]
class SouvenirsController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $manager,
        private SouvenirsRepository $souvenirsRepository,
        private SerializerInterface $serializer,
    ){
    }

    // CREATE A SOUVENIR FOR A TRAVELBOOK
    #[Route(name: 'new', methods: ['POST'])]
    #[OA\Post(
        path: "/api/souvenirs",
        summary: "Create a souvenir for a travelbook",
        requestBody: new OA\RequestBody(
            description: "Souvenirs object that needs to be added to the travelbook",
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "what", type: "string", example: "Magnet"),
                    new OA\Property(property: "forWho", type: "string", example: "John"),
                    new OA\Property(property: "travelbook", type: "integer", example: 1),
                ]
            )
        ),
        tags: ["Souvenirs for a travelbook"],
        responses: [
            new OA\Response(
                response: "201",
                description: "Souvenir created",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "id", type: "integer", example: 1),
                        new OA\Property(property: "what", type: "string", example: "Magnet"),
                        new OA\Property(property: "forWho", type: "string", example: "John"),
                        new OA\Property(property: "travelbook", type: "integer", example: 1),
                    ]
                )
            ),
            new OA\Response(
                response: "400",
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

        $souvenirs = $this->serializer->deserialize($request->getContent(), Souvenirs::class, 'json');
        $souvenirs->setTravelbook($travelbook);

        $this->manager->persist($souvenirs);
        $this->manager->flush();

        return new JsonResponse(
            $this->serializer->serialize($souvenirs, 'json', ['groups' => 'souvenirs:read']),
            Response::HTTP_CREATED
        );
    }

    // SHOW A SOUVENIR FROM A TRAVELBOOK
    #[Route('/{id}', name: 'show', methods: ['GET'])]
    #[OA\Get(
        path: "/api/souvenirs/{id}",
        summary: "Show a souvenir from a travelbook",
        tags: ["Souvenirs for a travelbook"],
        parameters: [
            new OA\Parameter(
                name: "id",
                description: "ID of the souvenir to return",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer", example: 1)
            )
        ],
        responses: [
            new OA\Response(
                response: "200",
                description: "Souvenir found",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "id", type: "integer", example: 1),
                        new OA\Property(property: "what", type: "string", example: "Magnet"),
                        new OA\Property(property: "forWho", type: "string", example: "John"),
                        new OA\Property(property: "travelbook", type: "integer", example: 1),
                    ]
                )
            ),
            new OA\Response(
                response: "404",
                description: "Souvenir not found",
            )
        ]
    )]
    public function show(int $id): JsonResponse
    {
        $souvenirs = $this->souvenirsRepository->findOneBy(['id' => $id]);

        if (!$souvenirs) {
            return new JsonResponse(
                ['error' => 'Souvenir not found'],
                Response::HTTP_NOT_FOUND
            );
        }

        return new JsonResponse(
            $this->serializer->serialize($souvenirs, 'json', ['groups' => 'souvenirs:read']),
            Response::HTTP_OK
        );
    }


    // UPDATE A SOUVENIR FROM A TRAVELBOOK
    #[Route('/{id}', name: 'edit', methods: ['PUT'])]
    #[OA\Put(
        path: "/api/souvenirs/{id}",
        summary: "Update a souvenir by ID",
        requestBody: new OA\RequestBody(
            description: "Souvenirs object that needs to be updated in the travelbook",
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "what", type: "string", example: "Magnet"),
                    new OA\Property(property: "forWho", type: "string", example: "John"),
                    new OA\Property(property: "travelbook", type: "integer", example: 1),
                ]
            )
        ),
        tags: ["Souvenirs for a travelbook"],
        parameters: [
            new OA\Parameter(
                name: "id",
                description: "ID of the souvenir to update",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer", example: 1)
            )
        ],
        responses: [
            new OA\Response(
                response: "204",
                description: "Souvenir updated",
            ),
            new OA\Response(
                response: "404",
                description: "Souvenir not found",
            )
        ]
    )]
    public function edit(int $id, Request $request): JsonResponse
    {
        $souvenirs = $this->souvenirsRepository->findOneBy(['id' => $id]);

        if (!$souvenirs) {
            return new JsonResponse(
                ['error' => 'Souvenir not found'],
                Response::HTTP_NOT_FOUND
            );
        }

        $this->serializer->deserialize($request->getContent(), Souvenirs::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $souvenirs]);
        $this->manager->flush();

        return new JsonResponse(
            $this->serializer->serialize($souvenirs, 'json', ['groups' => 'souvenirs:read']),
            Response::HTTP_OK
        );
    }


    // DELETE A SOUVENIR FROM A TRAVELBOOK
    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    #[OA\Delete(
        path: "/api/souvenirs/{id}",
        summary: "Delete a souvenir by ID",
        tags: ["Souvenirs for a travelbook"],
        parameters: [
            new OA\Parameter(
                name: "id",
                description: "ID of the souvenir to delete",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer", example: 1)
            )
        ],
        responses: [
            new OA\Response(
                response: "204",
                description: "Souvenir deleted",
            ),
            new OA\Response(
                response: "404",
                description: "Souvenir not found",
            )
        ]
    )]
    public function delete(int $id): JsonResponse
    {
        $souvenirs = $this->souvenirsRepository->findOneBy(['id' => $id]);

        if (!$souvenirs) {
            return new JsonResponse(
                ['error' => 'Souvenir not found'],
                Response::HTTP_NOT_FOUND
            );
        }

        $this->manager->remove($souvenirs);
        $this->manager->flush();

        return new JsonResponse(
            null,
            Response::HTTP_NO_CONTENT
        );
    }
}
