<?php

namespace App\Controller;

use App\Entity\FB;
use App\Entity\Travelbook;
use App\Repository\FBRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
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
        summary: "Add a new F&B place to a travelbook",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "name", type: "string", example: "Restaurant name example"),
                    new OA\Property(property: "address", type: "string", example: "Restaurant address example"),
                    new OA\Property(property: "visitAt", type: "datetime", example: "2021-12-31T23:59:59"),
                    new OA\Property(property: "travelbook", type: "integer", example: 1)
                ]
            )
        ),
        tags: ["F&B for a travelbook"],
        responses: [
            new OA\Response(
                response: 201,
                description: "F&B place added to travelbook",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "id", type: "integer", example: 1),
                        new OA\Property(property: "name", type: "string", example: "Restaurant name example"),
                        new OA\Property(property: "address", type: "string", example: "Restaurant address example"),
                        new OA\Property(property: "visitAt", type: "datetime", example: "2021-12-31T23:59:59"),
                        new OA\Property(property: "travelbook", type: "integer", example: 1)
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: "Travelbook not found"
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
        summary: "Get a F&B place by ID",
        tags: ["F&B for a travelbook"],
        responses: [
            new OA\Response(response: 200, description: "F&B place found"),
            new OA\Response(response: 404, description: "F&B place not found")
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
        summary: "Update a F&B place by ID",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "name", type: "string", example: "Restaurant name example"),
                    new OA\Property(property: "address", type: "string", example: "Restaurant address example"),
                    new OA\Property(property: "visitAt", type: "datetime", example: "2021-12-31T23:59:59"),
                    new OA\Property(property: "travelbook", type: "integer", example: 1)
                ]
            )
        ),
        tags: ["F&B for a travelbook"],
        parameters: [
            new OA\Parameter(
                name: "id",
                description: "ID of the F&B place to update",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer", example: 1)
            )
        ],
        responses: [
            new OA\Response(response: 200, description: "F&B place updated"),
            new OA\Response(response: 404, description: "F&B place not found")
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
        summary: "Delete a F&B place by ID",
        tags: ["F&B for a travelbook"],
        parameters: [
            new OA\Parameter(
                name: "id",
                description: "ID of the F&B place to delete",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer", example: 1)
            )
        ],
        responses: [
            new OA\Response(response: 200, description: "FB deleted"),
            new OA\Response(response: 404, description: "FB not found")
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
