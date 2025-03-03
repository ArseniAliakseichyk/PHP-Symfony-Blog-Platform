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
public function delete(Post $post, EntityManagerInterface $entityManager): Response
{
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
