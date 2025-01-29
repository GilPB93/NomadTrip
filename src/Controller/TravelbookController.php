<?php

namespace App\Controller;

use App\Entity\Travelbook;
use App\Repository\TravelbookRepository;
use Doctrine\ORM\EntityManagerInterface;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;


#[Route('/api/travelbook', name: 'app_api_travelbook_')]
class TravelbookController extends AbstractController
{

    public function __construct(
        private TravelbookRepository $travelbookRepository,
        private EntityManagerInterface $manager,
        private SerializerInterface $serializer,
    ){
    }

    // CREATE A NEW TRAVELBOOK
    #[Route(name: 'new', methods: ['POST'])]
    #[OA\Post(
        path: "/api/travelbook",
        summary: "Create a new travelbook",
        requestBody: new OA\RequestBody(
            description: "Travelbook object that needs to be added",
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "title", type: "string", example: "My trip to Paris"),
                    new OA\Property(property: "departureAt", type: "string", example: "2022-12-01"),
                    new OA\Property(property: "comebackAt", type: "string", example: "2022-12-10"),
                    new OA\Property(property: "flightNumber", type: "string", example: "AF1234"),
                    new OA\Property(property: "accommodation", type: "string", example: "Hotel"),
                    new OA\Property(property: "user", type: "integer", example: 1),
                ]
            )
        ),
        tags: ["Travelbook"],
        responses: [
            new OA\Response(
                response: "201",
                description: "Travelbook created",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "id", type: "integer", example: 1),
                        new OA\Property(property: "title", type: "string", example: "My trip to Paris"),
                        new OA\Property(property: "departureAt", type: "string", example: "2022-12-01"),
                        new OA\Property(property: "comebackAt", type: "string", example: "2022-12-10"),
                        new OA\Property(property: "flightNumber", type: "string", example: "AF1234"),
                        new OA\Property(property: "accommodation", type: "string", example: "Hotel"),
                        new OA\Property(property: "createdAt", type: "string", example: "2022-12-01T00:00:00+00:00"),
                        new OA\Property(property: "updatedAt", type: "string", example: "2022-12-01T00:00:00+00:00"),
                        new OA\Property(property: "user", type: "integer", example: 1),
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
        $travelbook = $this->serializer->deserialize($request->getContent(), Travelbook::class, 'json');
        $travelbook->setCreatedAt(new \DateTimeImmutable());
        $travelbook->setUpdatedAt(new \DateTimeImmutable());

        $this->manager->persist($travelbook);
        $this->manager->flush();

        return new JsonResponse($this->serializer->serialize($travelbook, 'json'), Response::HTTP_CREATED, [], true);
    }


    // SHOW ONE TRAVELBOOK
    #[Route('/{id}', name: 'show', methods: ['GET'])]
    #[OA\Get(
        path: "/api/travelbook/{id}",
        summary: "Show one travelbook",
        tags: ["Travelbook"],
        parameters: [
            new OA\Parameter(
                name: "id",
                description: "ID of the travelbook to return",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(
                response: "200",
                description: "Travelbook found",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "id", type: "integer", example: 1),
                        new OA\Property(property: "title", type: "string", example: "My trip to Paris"),
                        new OA\Property(property: "departureAt", type: "string", example: "2022-12-01"),
                        new OA\Property(property: "comebackAt", type: "string", example: "2022-12-10"),
                        new OA\Property(property: "flightNumber", type: "string", example: "AF1234"),
                        new OA\Property(property: "accommodation", type: "string", example: "Hotel"),
                        new OA\Property(property: "createdAt", type: "string", example: "2022-12-01T00:00:00+00:00"),
                        new OA\Property(property: "updatedAt", type: "string", example: "2022-12-01T00:00:00+00:00"),
                        new OA\Property(property: "user", type: "integer", example: 1),
                    ]
                )
            ),
            new OA\Response(
                response: "404",
                description: "Travelbook not found",
            )
        ]
    )]
    public function getTravelbook(int $id): Response
    {
        $travelbook = $this->travelbookRepository->find($id);

        if (!$travelbook) {
            return new JsonResponse(
                ['message' => 'Travelbook not found'],
                Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse(
            $this->serializer->serialize($travelbook, 'json'),
            Response::HTTP_OK
        );
    }


    // EDIT A TRAVELBOOK
    #[Route('/{id}', name: 'edit', methods: ['PUT'])]
    #[OA\Put(
        path: "/api/travelbook/{id}",
        summary: "Edit a travelbook",
        requestBody: new OA\RequestBody(
            description: "Travelbook object that needs to be edited",
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "title", type: "string", example: "My trip to Paris"),
                    new OA\Property(property: "departureAt", type: "string", example: "2022-12-01"),
                    new OA\Property(property: "comebackAt", type: "string", example: "2022-12-10"),
                    new OA\Property(property: "flightNumber", type: "string", example: "AF1234"),
                    new OA\Property(property: "accommodation", type: "string", example: "Hotel"),
                    new OA\Property(property: "user", type: "integer", example: 1),
                ]
            )
        ),
        tags: ["Travelbook"],
        parameters: [
            new OA\Parameter(
                name: "id",
                description: "ID of the travelbook to edit",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(
                response: "200",
                description: "Travelbook updated",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "id", type: "integer", example: 1),
                        new OA\Property(property: "title", type: "string", example: "My trip to Paris"),
                        new OA\Property(property: "departureAt", type: "string", example: "2022-12-01"),
                        new OA\Property(property: "comebackAt", type: "string", example: "2022-12-10"),
                        new OA\Property(property: "flightNumber", type: "string", example: "AF1234"),
                        new OA\Property(property: "accommodation", type: "string", example: "Hotel"),
                        new OA\Property(property: "createdAt", type: "string", example: "2022-12-01T00:00:00+00:00"),
                        new OA\Property(property: "updatedAt", type: "string", example: "2022-12-01T00:00:00+00:00"),
                        new OA\Property(property: "user", type: "integer", example: 1),
                    ]
                )
            ),
            new OA\Response(
                response: "404",
                description: "Travelbook not found",
            )
        ]
    )]
    public function edit(int $id, Request $request): Response
    {
        $travelbook = $this->travelbookRepository->findOneBy(['id' => $id]);

        if (!$travelbook) {
            return new JsonResponse(
                ['message' => 'Travelbook not found'],
                Response::HTTP_NOT_FOUND
            );
        }

        $this->serializer->deserialize($request->getContent(), Travelbook::class, 'json', ['object_to_populate' => $travelbook]);
        $travelbook->setUpdatedAt(new \DateTimeImmutable());
        $this->manager->flush();

        return new JsonResponse(
            $this->serializer->serialize($travelbook, 'json'),
            Response::HTTP_OK
        );
    }


    // DELETE A TRAVELBOOK
    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    #[OA\Delete(
        path: "/api/travelbook/{id}",
        summary: "Delete a travelbook by ID",
        tags: ["Travelbook"],
        parameters: [
            new OA\Parameter(
                name: "id",
                description: "ID of the travelbook to delete",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(
                response: "204",
                description: "Travelbook deleted",
            ),
            new OA\Response(
                response: "404",
                description: "Travelbook not found",
            )
        ]
    )]
    public function delete(int $id): Response
    {
        $travelbook = $this->travelbookRepository->findOneBy(['id' => $id]);

        if (!$travelbook) {
            return new JsonResponse(
                ['message' => 'Travelbook not found'],
                Response::HTTP_NOT_FOUND
            );
        }

        $this->manager->remove($travelbook);
        $this->manager->flush();

        return new JsonResponse(
            ['message' => 'Travelbook deleted'],
            Response::HTTP_NO_CONTENT
        );
    }
}
