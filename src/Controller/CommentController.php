<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Form\CommentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class CommentController extends AbstractController
{
    #[Route('/post/{id}/comment/add', name: 'comment_add', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function add(
        Post $post,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setAuthor($this->getUser());
            $comment->setPost($post);

            $entityManager->persist($comment);
            $entityManager->flush();

            $this->addFlash('success', 'Comment added!');
        } else {
            $this->addFlash('error', 'Error adding comment');
        }

        return $this->redirectToRoute('home');
    }

    #[Route('/comment/{id}/edit', name: 'comment_edit', methods: ['GET', 'POST'])]
    #[IsGranted('COMMENT_EDIT', subject: 'comment')]
    public function edit(
        Comment $comment,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Comment updated!');
            return $this->redirectToRoute('home');
        }

        return $this->render('comment/edit.html.twig', [
            'form' => $form->createView(),
            'comment' => $comment,
        ]);
    }

    #[Route('/comment/{id}/delete', name: 'comment_delete', methods: ['POST'])]
    #[IsGranted('COMMENT_DELETE', subject: 'comment')]
    public function delete(
        Comment $comment,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $csrfToken = $request->request->get('_token');
        if (!$this->isCsrfTokenValid('delete-comment' . $comment->getId(), $csrfToken)) {
            throw $this->createAccessDeniedException('Invalid CSRF token');
        }

        $entityManager->remove($comment);
        $entityManager->flush();

        $this->addFlash('success', 'Comment deleted!');
        return $this->redirectToRoute('home');
    }
}
