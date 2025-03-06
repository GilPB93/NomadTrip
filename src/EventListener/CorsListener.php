<?php

namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpFoundation\Response;

class CorsListener
{
    public function onKernelResponse(ResponseEvent $event)
    {
        $response = $event->getResponse();
        if (!$response) {
            return;
        }

        $request = $event->getRequest();
        $origin = $request->headers->get('Origin');

        // Vérifier si l'origine est autorisée
        $allowedOrigins = [
            'https://nomadtripfrontend-934f654ec662.herokuapp.com',
            'http://localhost:3000'
        ];

        if (in_array($origin, $allowedOrigins, true)) {
            $response->headers->set('Access-Control-Allow-Origin', $origin);
        }

        $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, OPTIONS, PUT, DELETE, PATCH');
        $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-AUTH-TOKEN');
        $response->headers->set('Access-Control-Allow-Credentials', 'true');

        // Si la requête est une OPTIONS, renvoyer une réponse vide avec un 204
        if ($request->isMethod('OPTIONS')) {
            $response->setStatusCode(Response::HTTP_NO_CONTENT);
            $response->setContent('');
        }
    }
}

