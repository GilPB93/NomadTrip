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
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;


#[Route('/api/travelbook', name: 'app_api_travelbook_')]
class TravelbookController extends AbstractController
{

    public function __construct(
        private TravelbookRepository $travelbookRepository,
        private EntityManagerInterface $manager,
        private SerializerInterface $serializer,
        private UrlGeneratorInterface $urlGenerator,
    ){
    }

    // LIST ALL TRAVELBOOKS BY USER
    #[Route('/user', name: 'user_travelbooks', methods: ['GET'])]
    #[OA\Get(
        path: "/api/travelbook/user",
        summary: "Get all travelbooks by user",
        tags: ["Travelbook"],
    )]
    public function getUserTravelbooks(#[CurrentUser] $user): JsonResponse
    {
        if (!$user) {
            return new JsonResponse(['message' => 'User not authenticated'], Response::HTTP_UNAUTHORIZED);
        }

        $travelbooks = $this->travelbookRepository->findBy(['user' => $user]);

        return new JsonResponse(
            $this->serializer->serialize($travelbooks, 'json', ['groups' => 'travelbook:read']),
            Response::HTTP_OK,
            [],
            true
        );
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
                    new OA\Property(property: "imgCouverture", description: "URL of the image in the cover of the travelbook", type: "string", example: "imgCouvertureFile=@/chemin/vers/image.jpg"),
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
                        new OA\Property(property: "imgCouverture", description: "URL of the image in the cover of the travelbook", type: "string", example: "imgCouvertureFile=@/chemin/vers/image.jpg"),
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
    public function new(Request $request, #[CurrentUser] $user): JsonResponse
    {
        $data = $request->request->all();

        if (empty($data['title']) || empty($data['departureAt']) || empty($data['comebackAt'])) {
            return new JsonResponse(['error' => 'Missing required fields'], Response::HTTP_BAD_REQUEST);
        }

        $travelbook = new Travelbook();
        $travelbook->setTitle($data['title'] ?? null);
        $travelbook->setDepartureAt(new \DateTimeImmutable($data['departureAt'] ?? 'now'));
        $travelbook->setComebackAt(new \DateTimeImmutable($data['comebackAt'] ?? 'now'));
        $travelbook->setFlightNumber($data['flightNumber'] ?? null);
        $travelbook->setAccommodation($data['accommodation'] ?? null);
        $travelbook->setUser($user);
        $travelbook->setCreatedAt(new \DateTimeImmutable());
        $travelbook->setUpdatedAt(new \DateTimeImmutable());

        // Gestion du fichier image
        $imageFile = $request->files->get('imgCouvertureFile');
        if ($imageFile) {
            $travelbook->setImgCouvertureFile($imageFile);
        }

        $this->manager->persist($travelbook);
        $this->manager->flush();

        return new JsonResponse(
            $this->serializer->serialize($travelbook, 'json', ['groups' => 'travelbook:read']),
            Response::HTTP_CREATED,
            [],
            true
        );
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
                        new OA\Property(property: "imgCouverture", description: "URL of the image in the cover of the travelbook", type: "string", example: "imgCouvertureFile=@/chemin/vers/image.jpg"),
                        new OA\Property(property: "departureAt", type: "string", example: "2022-12-01"),
                        new OA\Property(property: "comebackAt", type: "string", example: "2022-12-10"),
                        new OA\Property(property: "flightNumber", type: "string", example: "AF1234"),
                        new OA\Property(property: "accommodation", type: "string", example: "Hotel"),
                        new OA\Property(property: "places", type: "array",
                            items: new OA\Items(type: "object")
                        ),
                        new OA\Property(property: "fBs", type: "array",
                            items: new OA\Items(type: "object")
                        ),
                        new OA\Property(property: "photos", type: "array",
                            items: new OA\Items(type: "object")
                        ),
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
            return new JsonResponse(['message' => 'Travelbook not found'], Response::HTTP_NOT_FOUND);
        }

        $travelbookData = $this->serializer->normalize($travelbook, 'json', ['groups' => ['travelbook:read']]);

        if ($travelbook->getImgCouverture()) {
            $travelbookData['imgCouverture'] = $this->urlGenerator->generate(
                    'app_api_travelbook_show',
                    ['id' => $travelbook->getId()],
                    UrlGeneratorInterface::ABSOLUTE_URL
                ) . '/uploads/images/travelbooks/' . $travelbook->getImgCouverture();
        }

        return new JsonResponse($travelbookData, Response::HTTP_OK);
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
                    new OA\Property(property: "imgCouverture", description: "URL of the image in the cover of the travelbook", type: "string", example: "imgCouvertureFile=@/chemin/vers/image.jpg"),
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
                        new OA\Property(property: "imgCouverture", description: "URL of the image in the cover of the travelbook", type: "string", example: "imgCouvertureFile=@/chemin/vers/image.jpg"),
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
        $travelbook = $this->travelbookRepository->find($id);

        if (!$travelbook) {
            return new JsonResponse(
                ['message' => 'Travelbook not found'],
                Response::HTTP_NOT_FOUND
            );
        }

        $this->serializer->deserialize($request->getContent(), Travelbook::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $travelbook]);
        $travelbook->setUpdatedAt(new \DateTimeImmutable());

        $this->manager->flush();

        return new JsonResponse(
            $this->serializer->serialize($travelbook, 'json', ['groups' => 'travelbook:read']),
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

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }


    // GET LIST OF TRAVELBOOKS
    #[Route('/all', name: 'list', methods: ['GET'])]
    #[isGranted('ROLE_ADMIN')]
    #[OA\Get(
        path: "/api/travelbook/all",
        summary: "Récupérer la liste des carnets de voyage",
        tags: ["Carnet de Voyage"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Liste des carnets de voyage récupérée avec succès",
                content: new OA\JsonContent(type: "array", items: new OA\Items(ref: "#/components/schemas/Travelbook"))
            )
        ]
    )]
    public function getTravelbooks(): JsonResponse
    {
        $travelbooks = $this->travelbookRepository->findAll();
        return new JsonResponse($this->serializer->serialize($travelbooks, 'json', ['groups' => 'travelbook:read']), 200, [], true);
    }


    // GET DETAILS OF TRAVELBOOKS
    #[Route('/details/{id}', name: 'details', methods: ['GET'])]
    #[isGranted('ROLE_ADMIN')]
    #[OA\Get(
        path: "/api/travelbook/details/{id}",
        summary: "Récupérer les détails d'un carnet de voyage",
        tags: ["Carnet de Voyage"],
        parameters: [
            new OA\Parameter(
                name: "id",
                description: "ID du carnet de voyage",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Détails du carnet de voyage récupérés",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "id", type: "integer", example: 1),
                        new OA\Property(property: "title", type: "string", example: "Voyage à Paris"),
                        new OA\Property(property: "departureAt", type: "string", format: "date", example: "2023-06-01"),
                        new OA\Property(property: "comebackAt", type: "string", format: "date", example: "2023-06-10"),
                        new OA\Property(property: "createdAt", type: "string", format: "date-time", example: "2023-05-15T14:30:00Z"),
                        new OA\Property(property: "user", type: "integer", example: 1)
                    ]
                ),
            ),
            new OA\Response(response: 404, description: "Carnet de voyage non trouvé")
        ]
    )]
    public function getTravelbookDetails(int $id): JsonResponse
    {
        $travelbook = $this->travelbookRepository->find($id);
        if (!$travelbook) {
            return new JsonResponse(['message' => 'Carnet de voyage non trouvé'], 404);
        }

        return new JsonResponse($this->serializer->serialize($travelbook, 'json', ['groups' => 'travelbook:read']), 200, [], true);
    }

}
