<?php

namespace App\Controller;

use App\Entity\ContactMessage;
use App\Repository\ContactMessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use OpenApi\Attributes as OA;

#[Route('/api/contact', name: 'app_api_contact_')]
class ContactMessageController extends AbstractController
{
    #[Route('/send', methods: ['POST'])]
    #[OA\Post(
        path: "/api/contact/send",
        summary: "Send an email with all the message's data",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "name", type: "string"),
                    new OA\Property(property: "email", type: "string"),
                    new OA\Property(property: "subject", type: "string"),
                    new OA\Property(property: "message", type: "string")
                ]
            )
        ),
        responses: [new OA\Response(response: 200, description: "Email envoyé")]
    )]
    public function sendContactEmail(Request $request, MailerInterface $mailer): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $email = (new Email())
            ->from($data['email'])
            ->to('admin@nomadtrip.com')
            ->subject($data['subject'])
            ->text($data['message']);

        $mailer->send($email);

        return new JsonResponse(['message' => 'Email envoyé'], 200);
    }

    #[Route('/save', methods: ['POST'])]
    #[OA\Post(
        path: "/api/contact/save",
        summary: "Save a contact message",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "name", type: "string"),
                    new OA\Property(property: "email", type: "string"),
                    new OA\Property(property: "subject", type: "string"),
                    new OA\Property(property: "message", type: "string")
                ]
            )
        ),
        responses: [new OA\Response(response: 201, description: "Message enregistré")]
    )]
    public function saveMessage(Request $request, EntityManagerInterface $entityManager): JsonResponse
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

    #[Route('/all', methods: ['GET'])]
    #[OA\Get(
        path: "/api/contact/all",
        summary: "Get all contact messages",
        responses: [new OA\Response(response: 200, description: "Liste des messages")]
    )]
    public function getContactMessages(ContactMessageRepository $repository): JsonResponse
    {
        $messages = $repository->findAll();
        return new JsonResponse($messages, 200);
    }
}
