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
        responses: [
            new OA\Response(response: 201, description: "Message enregistré")
        ]
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
        responses: [
            new OA\Response(response: 200, description: "Liste des messages")
        ]
    )]
    public function get(ContactMessageRepository $repository): JsonResponse
    {
        $messages = $repository->findBy(['status' => 'unread']); // Filtrer uniquement les messages non répondus

        $data = array_map(function (ContactMessage $message) {
            return [
                'id' => $message->getId(),
                'name' => $message->getName(),
                'email' => $message->getEmail(),
                'subject' => $message->getSubject(),
                'message' => $message->getMessage(),
                'sentAt' => $message->getSentAt()->format('Y-m-d H:i:s'),
                'status' => $message->getStatus(),
            ];
        }, $messages);

        return new JsonResponse($data, 200);
    }

    #[Route('/reply/{id}', name: 'replyContactMessage', methods: ['PATCH'])]
    #[OA\Patch(
        path: "/api/contact/reply/{id}",
        summary: "Mark a contact message as replied",
        tags: ["Contact Message"],
        responses: [
            new OA\Response(response: 200, description: "Message changed to replied"),
            new OA\Response(response: 404, description: "Message not found")]
    )]
    public function markAsReplied(int $id, ContactMessageRepository $repository, EntityManagerInterface $entityManager): JsonResponse
    {
        $message = $repository->find($id);

        if (!$message) {
            return new JsonResponse(['error' => 'Message non trouvé'], 404);
        }

        $message->setStatus('replied');
        $entityManager->persist($message);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Message marqué comme répondu'], 200);
    }

    #[Route('/replied', name: 'getRepliedMessages', methods: ['GET'])]
    #[OA\Get(
        path: "/api/contact/replied/{id}",
        summary: "Get all replied contact messages",
        tags: ["Contact Message"],
        responses: [
            new OA\Response(response: 200, description: "List of replied messages")
        ]
    )]
    public function getRepliedMessages(ContactMessageRepository $repository): JsonResponse
    {
        $messages = $repository->findBy(['status' => 'replied']); // Filtrer uniquement les messages répondus

        $data = array_map(function (ContactMessage $message) {
            return [
                'id' => $message->getId(),
                'name' => $message->getName(),
                'email' => $message->getEmail(),
                'subject' => $message->getSubject(),
                'message' => $message->getMessage(),
                'sentAt' => $message->getSentAt()->format('Y-m-d H:i:s'),
                'status' => $message->getStatus(),
            ];
        }, $messages);

        return new JsonResponse($data, 200);
    }


}
