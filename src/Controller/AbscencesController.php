<?php

namespace App\Controller;

use App\Entity\Abscences;
use App\Entity\Players;
use App\Entity\User;
use App\Form\AbscencesType;
use App\Repository\AbscencesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/abscences')]
#[IsGranted('ROLE_USER')]
final class AbscencesController extends AbstractController
{
    #[Route(name: 'app_abscences_index', methods: ['GET'])]
    public function index(AbscencesRepository $abscencesRepository): Response
    {
        $this->requireUser();

        return $this->render('abscences/index.html.twig', [
            'abscences' => $abscencesRepository->findAllOrderedForListing(),
        ]);
    }

    #[Route('/new/joueur/{player}', name: 'app_abscences_new_for_player', methods: ['GET', 'POST'], requirements: ['player' => '\\d+'])]
    public function newForPlayer(Request $request, Players $player, EntityManagerInterface $entityManager): Response
    {
        return $this->processNew($request, $entityManager, $player);
    }

    #[Route('/new', name: 'app_abscences_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        return $this->processNew($request, $entityManager, null);
    }

    private function processNew(Request $request, EntityManagerInterface $entityManager, ?Players $presetPlayer): Response
    {
        $user = $this->requireUser();

        $abscence = new Abscences();
        if ($presetPlayer !== null) {
            $abscence->setPlayers($presetPlayer);
        }

        $form = $this->createForm(AbscencesType::class, $abscence, [
            'preset_player' => $presetPlayer !== null,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $abscence->setUser($user);
            $entityManager->persist($abscence);
            $entityManager->flush();

            return $this->redirectToRoute('app_abscences_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('abscences/new.html.twig', [
            'abscence' => $abscence,
            'form' => $form,
            'preset_player' => $presetPlayer,
        ]);
    }

    #[Route('/{id}', name: 'app_abscences_show', methods: ['GET'], requirements: ['id' => '\\d+'])]
    public function show(Abscences $abscence): Response
    {
        $this->requireUser();

        return $this->render('abscences/show.html.twig', [
            'abscence' => $abscence,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_abscences_edit', methods: ['GET', 'POST'], requirements: ['id' => '\\d+'])]
    public function edit(Request $request, Abscences $abscence, EntityManagerInterface $entityManager): Response
    {
        $user = $this->requireUser();
        $this->denyUnlessOwner($abscence, $user);

        $form = $this->createForm(AbscencesType::class, $abscence, [
            'preset_player' => true,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_abscences_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('abscences/edit.html.twig', [
            'abscence' => $abscence,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_abscences_delete', methods: ['POST'], requirements: ['id' => '\\d+'])]
    public function delete(Request $request, Abscences $abscence, EntityManagerInterface $entityManager): Response
    {
        $this->denyUnlessOwner($abscence, $this->requireUser());

        if ($this->isCsrfTokenValid('delete'.$abscence->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($abscence);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_abscences_index', [], Response::HTTP_SEE_OTHER);
    }

    private function requireUser(): User
    {
        $user = $this->getUser();
        if (!$user instanceof User) {
            throw $this->createAccessDeniedException();
        }

        return $user;
    }

    private function denyUnlessOwner(Abscences $abscence, User $user): void
    {
        if ($abscence->getUser() === null || $abscence->getUser()->getId() !== $user->getId()) {
            throw $this->createAccessDeniedException();
        }
    }
}
