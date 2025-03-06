<?php

namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\ResponseEvent;

class CorsListener
{
    public function onKernelResponse(ResponseEvent $event)
    {
        $response = $event->getResponse();
        $request = $event->getRequest();

        if (!$response) {
            return;
        }

        // Détecter l'origine de la requête
        $origin = $request->headers->get('Origin');

        // Définir les origines autorisées
        $allowedOrigins = [
            'https://nomadtripfrontend-934f654ec662.herokuapp.com',
            'http://localhost:3000'
        ];

        // Ajouter les headers CORS si l'origine est autorisée
        if (in_array($origin, $allowedOrigins, true)) {
            $response->headers->set('Access-Control-Allow-Origin', $origin);
        }

        // Ajouter tous les autres headers nécessaires
        $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, OPTIONS, PUT, DELETE, PATCH');
        $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-AUTH-TOKEN');
        $response->headers->set('Access-Control-Allow-Credentials', 'true');

        // Si la requête est une OPTIONS (preflight), retourner une réponse vide avec un 204
        if ($request->isMethod('OPTIONS')) {
            $response->setStatusCode(204);
            $response->setContent('');
        }
    }
}


