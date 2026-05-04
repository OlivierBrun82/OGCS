<?php

namespace App\Controller;

use App\Entity\Blames;
use App\Form\BlamesType;
use App\Repository\BlamesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/blames')]
final class BlamesController extends AbstractController
{
    #[Route(name: 'app_blames_index', methods: ['GET'])]
    public function index(BlamesRepository $blamesRepository): Response
    {
        return $this->render('blames/index.html.twig', [
            'blames' => $blamesRepository->findAllRecentFirst(),
        ]);
    }

    #[Route('/new', name: 'app_blames_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $blame = new Blames();
        $form = $this->createForm(BlamesType::class, $blame);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($blame);
            $entityManager->flush();

            return $this->redirectToRoute('app_blames_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('blames/new.html.twig', [
            'blame' => $blame,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_blames_show', methods: ['GET'])]
    public function show(Blames $blame): Response
    {
        return $this->render('blames/show.html.twig', [
            'blame' => $blame,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_blames_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Blames $blame, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(BlamesType::class, $blame);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_blames_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('blames/edit.html.twig', [
            'blame' => $blame,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_blames_delete', methods: ['POST'])]
    public function delete(Request $request, Blames $blame, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$blame->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($blame);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_blames_index', [], Response::HTTP_SEE_OTHER);
    }
}
