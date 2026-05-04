<?php

namespace App\Controller;

use App\Entity\Players;
use App\Form\PlayerType;
use App\Repository\PlayersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('players')]
final class PlayersController extends AbstractController
{
    // Endpoint pour afficher tout les joueurs
    #[Route('/', name: 'players_index')]
    public function index(PlayersRepository $playersRepository): Response

    {
        return $this->render('players/index.html.twig', [
            'players' => $playersRepository->findAll(),
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
    public function update(Players $players, Request $request, EntityManagerInterface $em): Response

    {
        $formPlayer = $this->createForm(PlayerType::class, $players);
        $formPlayer->handleRequest($request);

        if ($formPlayer->isSubmitted() && $formPlayer->isValid())
        {
            $em->flush();
            return $this->redirectToRoute('player_show', ['id' => $players->getId()]);
        }

        return $this->render('players/update.html.twig', [
            'formPlayer' => $formPlayer,
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
