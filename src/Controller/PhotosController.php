<?php

namespace App\Controller;

use App\Entity\Photos;
use App\Entity\Travelbook;
use App\Repository\PhotosRepository;
use Doctrine\ORM\EntityManagerInterface;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/photos', name: 'app_api_photos_')]
class PhotosController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $manager,
        private PhotosRepository $photosRepository,
        private SerializerInterface $serializer,

    ){
    }

    // CREATE PHOTO FOR A TRAVELBOOK
    #[Route(name: 'new', methods: ['POST'])]
    #[OA\Post(
        path: '/api/photos',
        summary: 'Create a new photo for a travelbook',
        requestBody: new OA\RequestBody(
            description: 'Photo object that needs to be added',
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'imgUrl', description: 'The URL of the photo', type: 'string', example: 'https://example.com/image.jpg'),
                    new OA\Property(property: 'travelbook', type: 'integer', example: 1)
                ]
            )
        ),
        tags: ['Photos for a travelbook'],
        responses: [
            new OA\Response(
                response: '201',
                description: 'Photo created',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'id', type: 'integer', example: 1),
                        new OA\Property(property: 'imgUrl', type: 'string', example: 'https://example.com/image.jpg'),
                        new OA\Property(property: 'addedAt', type: 'string', format: 'date-time', example: '2021-09-01T12:00:00Z'),
                        new OA\Property(property: 'travelbook', type: 'integer', example: 1)
                    ]
                )
            ),
            new OA\Response(
                response: '400',
                description: 'Image or Travelbook ID missing'
            ),
            new OA\Response(
                response: '404',
                description: 'Travelbook not found'
            )
        ]

    )]
    public function new(Request $request): JsonResponse
    {
        $photoFile = $request->files->get('imgUrl');
        $travelbookId = $request->request->get('travelbook');

        if (!$photoFile || !$travelbookId) {
            return new JsonResponse(['error' => 'Image ou Travelbook ID manquant'], Response::HTTP_BAD_REQUEST);
        }

        $travelbook = $this->manager->getRepository(Travelbook::class)->find($travelbookId);
        if (!$travelbook) {
            return new JsonResponse(['error' => 'Travelbook non trouvé'], Response::HTTP_BAD_REQUEST);
        }

        $photo = new Photos();
        $photo->setAddedAt(new \DateTimeImmutable());
        $photo->setTravelbook($travelbook);

        $photoFile = $request->files->get('imgUrl');
        if ($photoFile) {
            $photo->setImgUrlFile($photoFile);
        }

        $this->manager->persist($photo);
        $this->manager->flush();

        return new JsonResponse(
            $this->serializer->serialize($photo, 'json', ['groups' => 'photos:read']),
            Response::HTTP_CREATED,
            [],
            true
        );
    }


    // SHOW A PHOTO FROM A TRAVELBOOK
    #[Route('/{id}', name: 'show', methods: ['GET'])]
    #[OA\Get(
        path: '/api/photos/{id}',
        summary: 'Get a photo by ID',
        tags: ['Photos for a travelbook'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'The ID of the photo',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer'),
                example: 1
            )
        ],
        responses: [
            new OA\Response(
                response: '200',
                description: 'Photo found',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'id', type: 'integer', example: 1),
                        new OA\Property(property: 'imgUrl', type: 'string', example: 'https://example.com/image.jpg'),
                        new OA\Property(property: 'addedAt', type: 'string', format: 'date-time', example: '2021-09-01T12:00:00Z'),
                        new OA\Property(property: 'travelbook', type: 'integer', example: 1)
                    ]
                )
            ),
            new OA\Response(
                response: '404',
                description: 'Photo not found'
            )
        ]
    )]
    public function show(int $id): JsonResponse
    {
        $photos = $this->photosRepository->find($id);

        if (!$photos) {
            return new JsonResponse(
                ['error' => 'Photo not found'],
                Response::HTTP_NOT_FOUND
            );
        }

        $baseUrl = 'http://127.0.0.1:8000';
        $photoPath = $photos->getImgUrl()
            ? '/uploads/photos/' . $photos->getImgUrl()
            : null;
        $photoData['imgUrl'] = $photoPath ? $baseUrl . $photoPath : null;
        $photoData['addedAt'] = $photos->getAddedAt();
        $photoData['travelbook'] = $photos->getTravelbook()->getId();

        $photoData = $this->serializer->serialize($photos, 'json', ['groups' => 'photos:read']);

        return new JsonResponse(
            $photoData,
            Response::HTTP_OK
        );
    }

    // UPDATE A PHOTO FROM A TRAVELBOOK
    #[Route('/{id}', name: 'update', methods: ['PUT'])]
    #[OA\Put(
        path: '/api/photos/{id}',
        summary: 'Update a photo by ID',
        requestBody: new OA\RequestBody(
            description: 'Photo object that needs to be updated',
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'imgUrl', description: 'The URL of the photo', type: 'string', example: 'https://example.com/image.jpg'),
                    new OA\Property(property: 'addedAt', type: 'string', format: 'date-time', example: '2021-09-01T12:00:00Z'),
                    new OA\Property(property: 'travelbook', type: 'integer', example: 1)
                ]
            )
        ),
        tags: ['Photos for a travelbook'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'The ID of the photo',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer'),
                example: 1
            )
        ],
        responses: [
            new OA\Response(
                response: '200',
                description: 'Photo updated',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'id', type: 'integer', example: 1),
                        new OA\Property(property: 'imgUrl', type: 'string', example: 'https://example.com/image.jpg'),
                        new OA\Property(property: 'addedAt', type: 'string', format: 'date-time', example: '2021-09-01T12:00:00Z'),
                        new OA\Property(property: 'travelbook', type: 'integer', example: 1)
                    ]
                )
            ),
            new OA\Response(
                response: '404',
                description: 'Photo not found'
            )
        ]
    )]
    public function update(int $id, Request $request): JsonResponse
    {
        $photos = $this->photosRepository->findOneBy(['id' => $id]);

        if($photos) {
            $this->serializer->deserialize($request->getContent(), Photos::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $photos]);

            $this->manager->flush();

            return new JsonResponse(
                $this->serializer->serialize($photos, 'json', ['groups' => 'photos:read']),
                Response::HTTP_OK
            );
        }
        return new JsonResponse(
            null,
            Response::HTTP_NOT_FOUND
        );
    }

    // DELETE A PHOTO FROM A TRAVELBOOK
    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    #[OA\Delete(
        path: '/api/photos/{id}',
        summary: 'Delete a photo by ID',
        tags: ['Photos for a travelbook'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'The ID of the photo',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer'),
                example: 1
            )
        ],
        responses: [
            new OA\Response(
                response: '204',
                description: 'Photo deleted'
            ),
            new OA\Response(
                response: '404',
                description: 'Photo not found'
            )
        ]
    )]
    public function delete(int $id): JsonResponse
    {
        $photo = $this->photosRepository->findOneBy(['id' => $id]);

        if (!$photo) {
            return new JsonResponse(['error' => 'Photo not found'], Response::HTTP_NOT_FOUND);
        }

        // 📌 Suppression du fichier sur le serveur
        $filesystem = new Filesystem();
        $filePath = $this->getParameter('kernel.project_dir') . '/public' . $photo->getImgUrl();
        if ($filesystem->exists($filePath)) {
            $filesystem->remove($filePath);
        }

        // 📌 Suppression de la photo en base de données
        $this->manager->remove($photo);
        $this->manager->flush();

        return new JsonResponse(['message' => 'Photo supprimée'], Response::HTTP_OK);
    }
}
