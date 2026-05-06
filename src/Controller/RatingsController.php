<?php

namespace App\Controller;

use App\Entity\Ratings;
use App\Entity\User;
use App\Form\RatingsType;
use App\Repository\RatingsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/ratings')]
#[IsGranted('ROLE_USER')]
final class RatingsController extends AbstractController
{
    #[Route('/', name: 'players_ratings_index', methods: ['GET'])]
    public function index(RatingsRepository $ratingsRepository): Response
    {
        return $this->render('ratings/index.html.twig', [
            'ratings' => $ratingsRepository->findAllRecentFirst(),
        ]);
    }

    #[Route('/new', name: 'players_ratings_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $em,
        RatingsRepository $ratingsRepository,
    ): Response {
        $user = $this->getUser();
        if (!$user instanceof User) {
            throw $this->createAccessDeniedException();
        }

        $rating = new Ratings();
        $rating->setCoach($user);
        $form = $this->createForm(RatingsType::class, $rating);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $player = $rating->getPlayer();
            $existing = $player !== null
                ? $ratingsRepository->findOneByCoachAndPlayer($user, $player)
                : null;

            if ($existing !== null) {
                $existing->setRating($rating->getRating());
                $existing->setMessage($rating->getMessage());
                $em->flush();

                return $this->redirectToRoute('players_ratings_show', ['id' => $existing->getId()], Response::HTTP_SEE_OTHER);
            }

            $rating->setCoach($user);
            $em->persist($rating);
            $em->flush();

            return $this->redirectToRoute('players_ratings_show', ['id' => $rating->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('ratings/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'players_ratings_show', methods: ['GET'], requirements: ['id' => '\\d+'])]
    public function show(Ratings $rating): Response
    {
        return $this->render('ratings/show.html.twig', [
            'rating' => $rating,
        ]);
    }

    #[Route('/{id}/edit', name: 'players_ratings_update', methods: ['GET', 'POST'], requirements: ['id' => '\\d+'])]
    public function update(
        Request $request,
        Ratings $rating,
        EntityManagerInterface $em,
    ): Response {
        $user = $this->getUser();
        if (!$user instanceof User || $rating->getCoach() !== $user) {
            throw $this->createAccessDeniedException('Vous ne pouvez modifier que vos propres notes.');
        }

        $form = $this->createForm(RatingsType::class, $rating);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('players_ratings_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('ratings/edit.html.twig', [
            'form' => $form,
            'rating' => $rating,
        ]);
    }

    #[Route('/{id}', name: 'players_ratings_delete', methods: ['POST'], requirements: ['id' => '\\d+'])]
    public function delete(Request $request, Ratings $rating, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        if (!$user instanceof User || $rating->getCoach() !== $user) {
            throw $this->createAccessDeniedException('Vous ne pouvez supprimer que vos propres notes.');
        }

        if ($this->isCsrfTokenValid('delete'.$rating->getId(), $request->getPayload()->getString('_token'))) {
            $em->remove($rating);
            $em->flush();
        }

        return $this->redirectToRoute('players_ratings_index', [], Response::HTTP_SEE_OTHER);
    }
}
