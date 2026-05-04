<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\User;
use App\Form\MessageType;
use App\Repository\MessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/messages')]
#[IsGranted('ROLE_USER')]
final class MessageController extends AbstractController
{
    #[Route('', name: 'app_messages_inbox', methods: ['GET'])]
    public function inbox(MessageRepository $messageRepository): Response
    {
        $user = $this->getUser();
        if (!$user instanceof User) {
            throw $this->createAccessDeniedException();
        }

        return $this->render('messages/inbox.html.twig', [
            'messages' => $messageRepository->findInboxForUser($user),
        ]);
    }

    #[Route('/envoyes', name: 'app_messages_sent', methods: ['GET'])]
    public function sent(MessageRepository $messageRepository): Response
    {
        $user = $this->getUser();
        if (!$user instanceof User) {
            throw $this->createAccessDeniedException();
        }

        return $this->render('messages/sent.html.twig', [
            'messages' => $messageRepository->findSentByUser($user),
        ]);
    }

    #[Route('/nouveau', name: 'app_messages_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        if (!$user instanceof User) {
            throw $this->createAccessDeniedException();
        }

        $message = new Message();
        $form = $this->createForm(MessageType::class, $message, [
            'current_user' => $user,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $message->setSender($user);
            $em->persist($message);
            $em->flush();

            $this->addFlash('success', 'Message envoyé.');

            return $this->redirectToRoute('app_messages_inbox', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('messages/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_messages_show', methods: ['GET'], requirements: ['id' => '\\d+'])]
    public function show(Message $message, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        if (!$user instanceof User || !$message->canBeReadBy($user)) {
            throw $this->createAccessDeniedException();
        }

        if ($message->getRecipient() === $user && $message->getReadAt() === null) {
            $message->markRead();
            $em->flush();
        }

        return $this->render('messages/show.html.twig', [
            'message' => $message,
        ]);
    }
}
