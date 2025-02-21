<?php

namespace App\Controller;

use App\Entity\ContactMessage;
use App\Repository\ContactMessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Attributes as OA;

#[Route('/api/contact', name: 'app_api_contact_')]
class ContactMessageController extends AbstractController
{
    #[Route('/save',name: 'saveContactMessage', methods: ['POST'])]
    #[OA\Post(
        path: "/api/contact/save",
        summary: "Save a contact message",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "name", type: "string", example: "Name example"),
                    new OA\Property(property: "email", type: "string", example: "example@email.com"),
                    new OA\Property(property: "subject", type: "string", example: "Subject example"),
                    new OA\Property(property: "message", type: "string", example: "Message example")
                ]
            )
        ),
        tags: ["Contact Message"],
        responses: [new OA\Response(response: 201, description: "Message enregistré")]
    )]
    public function save(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $contactMessage = new ContactMessage();
        $contactMessage->setName($data['name']);
        $contactMessage->setEmail($data['email']);
        $contactMessage->setSubject($data['subject']);
        $contactMessage->setMessage($data['message']);
        $contactMessage->setSentAt(new \DateTimeImmutable());

        $entityManager->persist($contactMessage);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Message enregistré'], 201);
    }

    #[Route('/all',name: 'getContactMessage', methods: ['GET'])]
    #[OA\Get(
        path: "/api/contact/all",
        summary: "Get all contact messages",
        tags: ["Contact Message"],
        responses: [new OA\Response(response: 200, description: "Liste des messages")]
    )]
    public function get(ContactMessageRepository $repository): JsonResponse
    {
        $messages = $repository->findAll();
        return new JsonResponse($messages, 200);
    }
}
