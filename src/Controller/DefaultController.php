<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('/{route}', name: 'frontend', requirements: ['route' => '^(?!api|bundles|uploads|css|js|img|scss).*$'], defaults: ['route' => ''])]
    public function index(): Response
    {
        $filePath = $this->getParameter('kernel.project_dir') . '/public/frontend/index.html';

        if (!file_exists($filePath)) {
            return new Response("Le fichier frontend/index.html est introuvable", 404);
        }

        return new Response(file_get_contents($filePath));
    }
}

