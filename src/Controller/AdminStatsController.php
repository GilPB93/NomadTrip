<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Repository\TravelbookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/admin/stats', name: 'admin_stats_')]
class AdminStatsController extends AbstractController
{
    public function __construct(
        private UserRepository $userRepository,
        private TravelbookRepository $travelbookRepository
    ) {
    }

    #[Route('/totals', name: 'totals', methods: ['GET'])]
    public function getTotals(): JsonResponse
    {
        $totalUsers = $this->userRepository->count([]);

        $totalTravelbooks = $this->travelbookRepository->count([]);

        $lastSignups = $this->userRepository->findBy([], ['createdAt' => 'DESC'], 5);

        $lastSignupData = array_map(function ($user) {
            return [
                'name' => $user->getFirstName() . ' ' . $user->getLastName(),
                'createdAt' => $user->getCreatedAt()->format('d/m/Y H:i') // Formatage de la date
            ];
        }, $lastSignups);

        return new JsonResponse([
            'totalUsers' => $totalUsers,
            'totalTravelbooks' => $totalTravelbooks,
            'lastSignups' => $lastSignupData
        ]);
    }
}
