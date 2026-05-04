<?php

namespace App\Controller;

use App\Repository\MatchesRepository;
use App\Repository\PlayersRepository;
use App\Repository\TeamsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    private const PREVIEW_LIMIT = 8;

    #[Route('/', name: 'app_home')]
    public function index(
        PlayersRepository $playersRepository,
        TeamsRepository $teamsRepository,
        MatchesRepository $matchesRepository,
    ): Response {
        return $this->render('home/index.html.twig', [
            'preview_players' => $playersRepository->findBy([], ['last_name' => 'ASC'], self::PREVIEW_LIMIT),
            'preview_teams' => $teamsRepository->findBy([], ['team_name' => 'ASC'], self::PREVIEW_LIMIT),
            'preview_matches' => $matchesRepository->findBy([], ['date' => 'DESC'], self::PREVIEW_LIMIT),
        ]);
    }
}
