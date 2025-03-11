<?php
namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class PostController extends AbstractController
{
    
    #[Route('/post/{id}/delete', name: 'post_delete', methods: ['POST'])]
#[IsGranted('POST_DELETE', subject: 'post')]
#[Route('/post/{id}/edit', name: 'post_edit', methods: ['GET', 'POST'])]
#[IsGranted('POST_EDIT', subject: 'post')]
public function edit(
    Request $request,
    Post $post,
    EntityManagerInterface $entityManager
): Response {
    $form = $this->createForm(PostType::class, $post);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->flush();

        $this->addFlash('success', 'Post updated!');
        return $this->redirectToRoute('home');
    }

    return $this->render('post/edit.html.twig', [
        'form' => $form->createView(),
        'post' => $post,
    ]);
}
public function delete(
    Request $request,
    Post $post,
    EntityManagerInterface $entityManager
): Response {
    $csrfToken = $request->request->get('_token');
    if (!$this->isCsrfTokenValid('delete'.$post->getId(), $csrfToken)) {
        throw $this->createAccessDeniedException('Invalid CSRF token');
    }
    $entityManager->remove($post);
    $entityManager->flush();

    $this->addFlash('success', 'Post deleted');
    return $this->redirectToRoute('home');
}

    #[Route('/post/new', name: 'post_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
{
    $post = new Post();
    
    $post->setAuthor($this->getUser());

    $form = $this->createForm(PostType::class, $post);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->persist($post);
        $entityManager->flush();

        $this->addFlash('success', 'Post created!');
        return $this->redirectToRoute('home');
    }

    return $this->render('post/new.html.twig', [
        'form' => $form->createView(),
    ]);
}
}
