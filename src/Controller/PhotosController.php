<?php

namespace App\Controller;

use App\Entity\Photos;
use App\Entity\Travelbook;
use App\Repository\PhotosRepository;
use App\Repository\TravelbookRepository;
use Doctrine\ORM\EntityManagerInterface;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
            description: 'Photo object that needs to be added to the travelbook',
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
                description: 'Invalid input'
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

        $photos = $this->serializer->deserialize($request->getContent(), Photos::class, 'json');
        $photos->setAddedAt(new \DateTimeImmutable());
        $photos->setTravelbook($travelbook);

        $this->manager->persist($photos);
        $this->manager->flush();

        return new JsonResponse(
            $this->serializer->serialize($photos, 'json', ['groups' => 'photos:read']),
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
        $photos = $this->photosRepository->findOneBy(['id' => $id]);

        if($photos) {
            return new JsonResponse(
                $this->serializer->serialize($photos, 'json', ['groups' => 'photos:read']),
                Response::HTTP_OK
            );
        }

        return new JsonResponse(
                'Photo not found',
                Response::HTTP_NOT_FOUND
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
        $photos = $this->photosRepository->findOneBy(['id' => $id]);

        if($photos) {
            $this->manager->remove($photos);
            $this->manager->flush();

            return new JsonResponse(
                null,
                Response::HTTP_NO_CONTENT
            );
        }
        return new JsonResponse(
            null,
            Response::HTTP_NOT_FOUND
        );
    }
}
