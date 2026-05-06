<?php

namespace App\Controller;

use App\Entity\Players;
use App\Entity\Ratings;
use App\Entity\User;
use App\Form\PlayerType;
use App\Form\RatingsType;
use App\Repository\PlayersRepository;
use App\Repository\RatingsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('players')]
final class PlayersController extends AbstractController
{
    // Endpoint pour afficher tout les joueurs
    #[Route('/', name: 'players_index')]
    public function index(PlayersRepository $playersRepository, RatingsRepository $ratingsRepository): Response
    {
        $players = $playersRepository->findAll();
        $coachRatingsByPlayerId = [];
        $user = $this->getUser();
        if ($user instanceof User) {
            $ids = array_values(array_filter(array_map(
                static fn (Players $p): ?int => $p->getId(),
                $players,
            )));
            $coachRatingsByPlayerId = $ratingsRepository->mapRatingValueByPlayerIdForCoach($user, $ids);
        }

        return $this->render('players/index.html.twig', [
            'players' => $players,
            'coachRatingsByPlayerId' => $coachRatingsByPlayerId,
        ]);
    }

    // Endpoint pour afficher un joueur par sont id en GET
    #[Route('/show/{id}', name: 'player_show')]
    public function show(Players $players)

    {
        return $this->render('/players/show.html.twig', [
            'players' => $players
        ]);
    }

    // Endpoint pour créer un joueur
    #[Route('/new/', name: 'player_new')]
    public function new(Request $request, EntityManagerInterface $em): Response

    {
        $newPlayer = new Players();

        $formPlayer = $this->createForm(PlayerType::class, $newPlayer);
        $formPlayer->handleRequest($request);

        if ($formPlayer->isSubmitted() && $formPlayer->isValid())
        
        {
            $em->persist($newPlayer);
            $em->flush();

            return $this->redirectToRoute('player_show', ['id' => $newPlayer->getId()]);
        }

        return $this->render('players/new.html.twig', [
            'formPlayer' => $formPlayer,
        ]);

    }

    // Endpoint pour mettre à jour un joueur
    #[Route('/update/{id}', name: 'player_update', methods: ['GET', 'POST'])]
    public function update(
        Players $players,
        Request $request,
        EntityManagerInterface $em,
        FormFactoryInterface $formFactory,
        RatingsRepository $ratingsRepository,
    ): Response {
        $user = $this->getUser();
        if (!$user instanceof User) {
            throw new \LogicException('Compte utilisateur invalide.');
        }

        $formPlayer = $this->createForm(PlayerType::class, $players);
        $formPlayer->handleRequest($request);

        $coachRating = $ratingsRepository->findOneByCoachAndPlayer($user, $players);
        if ($coachRating === null) {
            $coachRating = new Ratings();
            $coachRating->setCoach($user);
            $coachRating->setPlayer($players);
        }

        $coachRatingForm = $formFactory->createNamed(
            'coach_rating',
            RatingsType::class,
            $coachRating,
            ['hide_player' => true],
        );
        $coachRatingForm->handleRequest($request);

        if ($formPlayer->isSubmitted() && $formPlayer->isValid()) {
            $em->flush();

            return $this->redirectToRoute('player_show', ['id' => $players->getId()]);
        }

        if ($coachRatingForm->isSubmitted() && $coachRatingForm->isValid()) {
            if ($coachRating->getId() === null) {
                $em->persist($coachRating);
            }
            $em->flush();
            $this->addFlash('success', 'Votre note a été enregistrée.');

            return $this->redirectToRoute('player_update', ['id' => $players->getId()]);
        }

        return $this->render('players/update.html.twig', [
            'formPlayer' => $formPlayer,
            'coachRatingForm' => $coachRatingForm,
            'players' => $players,
        ]);
    }

    // Endpoint pour supprimer un joueur
    #[Route('/delete/{id}', name: 'player_delete', methods: ['POST'])]
    public function delete(Players $players, Request $request, EntityManagerInterface $em)

    {
        if ($this->isCsrfTokenValid('delete' . $players->getId(), $request->request->get('_token')))
        {
            $em->remove($players);
            $em->flush();
            return $this->redirectToRoute('players_index');
        }

    }

}
