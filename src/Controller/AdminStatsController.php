<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Repository\TravelbookRepository;
use OpenApi\Attributes as OA;
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

    // GET TOTALS OF USERS AND TRAVELBOOKS
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


    // GET LIST OF USERS
    #[Route('/list-users', name: 'list_users', methods: ['GET'])]
    public function fetchListUsers(): JsonResponse
    {
        $users = $this->userRepository->findAll();

        $usersData = array_map(function ($user) {
            return [
                'id' => $user->getId(),
                'name' => $user->getFirstName() . ' ' . $user->getLastName(),
                'email' => $user->getEmail(),
                'createdAt' => $user->getCreatedAt()->format('d/m/Y H:i')
            ];
        }, $users);

        return new JsonResponse($usersData);
    }


    // GET LIST OF TRAVELBOOKS GROUPED BY USER
    #[Route('/list-travelbooks', name: 'list_travelbooks', methods: ['GET'])]
    public function fetchListTravelbooks(): JsonResponse
    {
        $travelbooks = $this->travelbookRepository->findAll();

        $groupedTravelbooks = [];

        foreach ($travelbooks as $travelbook) {
            $user = $travelbook->getUser();
            $userName = $user ? $user->getFirstName() . ' ' . $user->getLastName() : 'Inconnu';

            if (!isset($groupedTravelbooks[$userName])) {
                $groupedTravelbooks[$userName] = [
                    'createdBy' => $userName,
                    'travelbooks' => []
                ];
            }

            $groupedTravelbooks[$userName]['travelbooks'][] = [
                'id' => $travelbook->getId(),
                'title' => $travelbook->getTitle(),
                'createdAt' => $travelbook->getCreatedAt()->format('d/m/Y H:i')
            ];
        }

        return new JsonResponse(array_values($groupedTravelbooks));
    }


    // GET MESSAGES

}
