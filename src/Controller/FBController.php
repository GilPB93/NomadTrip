<?php

namespace App\Controller;

use App\Entity\FB;
use App\Entity\Travelbook;
use App\Repository\FBRepository;
use App\Repository\TravelbookRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/fb', name: 'app_api_fb_')]
class FBController extends AbstractController
{
    public function __construct(
        private FBRepository $fbRepository,
        private SerializerInterface $serializer,
        private EntityManagerInterface $manager,
    ){
    }

    // CREATE NEW RESTAURANT/BAR FOR A TRAVELBOOK
    #[Route(name: 'new', methods: ['POST'])]
    #[OA\Post(
        path: "/api/fb",
        summary: "Create a new Restaurant/Bar for a Travelbook",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "name", type: "string", example: "Restaurant name test"),
                    new OA\Property(property: "address", type: "string", example: "Restaurant address test"),
                    new OA\Property(property: "visitAt", type: "string", format: "date-time", example: "2021-09-01T00:00:00+00:00"),
                    new OA\Property(property: "travelbook", type: "integer", example: 1)
                ]
            )
        ),
        tags: ["Restaurant/Bar for Travelbook"],
        responses: [
            new OA\Response(
                response: 201,
                description: "Restaurant/Bar created",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "id", type: "integer", example: 1),
                        new OA\Property(property: "name", type: "string", example: "Restaurant name test"),
                        new OA\Property(property: "address", type: "string", example: "Restaurant address test"),
                        new OA\Property(property: "visitAt", type: "string", format: "date-time", example: "2021-09-01T00:00:00+00:00"),
                        new OA\Property(property: "travelbook", type: "integer", example: 1)
                    ]
                )
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

        $fb = $this->serializer->deserialize($request->getContent(), FB::class, 'json');
        $fb->setVisitAt(new DateTimeImmutable($data['visitAt']));
        $fb->setTravelbook($travelbook);

        $this->manager->persist($fb);
        $this->manager->flush();

        return new JsonResponse(
            $this->serializer->serialize($fb, 'json', ['groups' => 'fb:read']),
            Response::HTTP_CREATED);
    }

    // SHOW A RESTAURANT/BAR BY ID
    #[Route('/{id}', name: 'show', methods: ['GET'])]
    #[OA\Get(
        path: "/api/fb/{id}",
        summary: "Get an Restaurant/Bar by ID",
        tags: ["Restaurant/Bar for Travelbook"],
        parameters: [
            new OA\Parameter(
                name: "id",
                description: "The Restaurant/Bar ID",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            ),
        ],
        responses: [
            new OA\Response(
                response:200,
                description:"Restaurant/Bar found",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "id", type: "integer", example: 1),
                        new OA\Property(property: "name", type: "string", example: "Restaurant name test"),
                        new OA\Property(property: "address", type: "string", example: "Restaurant address test"),
                        new OA\Property(property: "visitAt", type: "string", format: "date-time", example: "2021-09-01T00:00:00+00:00"),
                        new OA\Property(property: "travelbook", type: "integer", example: 1)
                    ]
                )
            ),
            new OA\Response(
                response:404,
                description:"Restaurant not found")
        ]
    )]
    public function show(int $id): JsonResponse
    {
        $fb = $this->fbRepository->findOneBy(['id' => $id]);
        if (!$fb) {
            return new JsonResponse(
                ['message' => 'FB not found'],
                Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse(
            $this->serializer->serialize($fb, 'json', ['groups' => 'fb:read']),
            Response::HTTP_OK);
    }

    // UPDATE A RESTAURANT/BAR BY ID
    #[Route('/{id}', name: 'update', methods: ['PUT'])]
    #[OA\Put(
        path: "/api/fb/{id}",
        summary: "Update an existing Restaurant/Bar",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "name", type: "string"),
                    new OA\Property(property: "address", type: "string"),
                    new OA\Property(property: "visitAt", type: "string", format: "date-time"),
                    new OA\Property(property: "travelbook", type: "integer")
                ]
            )
        ),
        tags: ["Restaurant/Bar for Travelbook"],
        parameters: [
            new OA\Parameter(
                name: "id",
                description: "The Restaurant/Bar ID",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            ),
        ],
        responses: [
            new OA\Response(
                response:200,
                description:"Restaurant/Bar updated",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "id", type: "integer", example: 1),
                        new OA\Property(property: "name", type: "string", example: "Restaurant name test"),
                        new OA\Property(property: "address", type: "string", example: "Restaurant address test"),
                        new OA\Property(property: "visitAt", type: "string", format: "date-time", example: "2021-09-01T00:00:00+00:00"),
                        new OA\Property(property: "travelbook", type: "integer", example: 1)
                    ]
                )
            ),
            new OA\Response(
                response:404,
                description:"Restaurant not found")
        ]
    )]
    public function update(int $id, Request $request): JsonResponse
    {
        $fb = $this->fbRepository->findOneBy(['id' => $id]);
        if ($fb) {
            $this->serializer->deserialize($request->getContent(), FB::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $fb]
            );

            $this->manager->persist($fb);
            $this->manager->flush();

            return new JsonResponse(
                $this->serializer->serialize($fb, 'json', ['groups' => 'fb:read']),
                Response::HTTP_OK);
        }

        return new JsonResponse(
            ['message' => 'FB not found'],
            Response::HTTP_NOT_FOUND);

    }

    // DELETE A RESTAURANT/BAR BY ID
    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    #[OA\Delete(
        path: "/api/fb/{id}",
        summary: "Delete an existing Restaurant/Bar",
        tags: ["Restaurant/Bar for Travelbook"],
        parameters: [
            new OA\Parameter(
                name: "id",
                description: "The Restaurant/Bar ID",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            ),
        ],
        responses: [
            new OA\Response(
                response:200,
                description:"Restaurant/Bar deleted"
            ),
            new OA\Response(
                response:404,
                description:"Restaurant not found"
            )
        ]
    )]
    public function delete(int $id): JsonResponse
    {
        $fb = $this->fbRepository->findOneBy(['id' => $id]);
        if ($fb) {
            $this->manager->remove($fb);
            $this->manager->flush();

            return new JsonResponse(
                ['message' => 'FB deleted'],
                Response::HTTP_OK);
        }

        return new JsonResponse(
            ['message' => 'FB not found'],
            Response::HTTP_NOT_FOUND);
    }
}
