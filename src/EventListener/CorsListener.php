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

        // Forcer le header Access-Control-Allow-Origin
        if ($origin && in_array($origin, $allowedOrigins, true)) {
            $response->headers->set('Access-Control-Allow-Origin', $origin);
        } else {
            $response->headers->set('Access-Control-Allow-Origin', '*'); // Optionnel : permet tout
        }

        // Ajouter les autres headers CORS
        $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, OPTIONS, PUT, DELETE, PATCH');
        $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-AUTH-TOKEN');
        $response->headers->set('Access-Control-Allow-Credentials', 'true');

        // Répondre immédiatement aux requêtes OPTIONS
        if ($request->isMethod('OPTIONS')) {
            $response->setStatusCode(204);
            $response->setContent('');
        }
    }
}
