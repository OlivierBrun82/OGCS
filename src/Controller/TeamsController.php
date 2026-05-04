<?php

namespace App\Controller;

use App\Entity\Teams;
use App\Form\TeamType;
use App\Repository\TeamsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('teams')]
final class TeamsController extends AbstractController
{
    // Endpoint pour afficher toutes les équipes
    #[Route('/', name: 'teams_index')]
    public function index(TeamsRepository $teamsRepository): Response
    {
        return $this->render('teams/index.html.twig', [
            'teams' => $teamsRepository->findAll(),
        ]);
    }

    // Endpoint pour afficher une équipe par son id en GET
    #[Route('/show/{id}', name: 'team_show')]
    public function show(Teams $teams): Response
    {
        return $this->render('teams/show.html.twig', [
            'teams' => $teams,
        ]);
    }

    // Endpoint pour créer une équipe
    #[Route('/new/', name: 'team_new')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $newTeam = new Teams();

        $formTeam = $this->createForm(TeamType::class, $newTeam);
        $formTeam->handleRequest($request);

        if ($formTeam->isSubmitted() && $formTeam->isValid()) {
            $em->persist($newTeam);
            $em->flush();

            return $this->redirectToRoute('team_show', ['id' => $newTeam->getId()]);
        }

        return $this->render('teams/new.html.twig', [
            'formTeam' => $formTeam,
        ]);
    }

    // Endpoint pour mettre à jour une équipe
    #[Route('/update/{id}', name: 'team_update', methods: ['GET', 'POST'])]
    public function update(Teams $teams, Request $request, EntityManagerInterface $em): Response
    {
        $formTeam = $this->createForm(TeamType::class, $teams);
        $formTeam->handleRequest($request);

        if ($formTeam->isSubmitted() && $formTeam->isValid()) {
            $em->flush();

            return $this->redirectToRoute('team_show', ['id' => $teams->getId()]);
        }

        return $this->render('teams/update.html.twig', [
            'formTeam' => $formTeam,
            'teams' => $teams,
        ]);
    }

    // Endpoint pour supprimer une équipe
    #[Route('/delete/{id}', name: 'team_delete', methods: ['POST'])]
    public function delete(Teams $teams, Request $request, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete' . $teams->getId(), $request->request->get('_token'))) {
            $em->remove($teams);
            $em->flush();

            return $this->redirectToRoute('teams_index');
        }

        return $this->redirectToRoute('teams_index');
    }
}
