<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Entity\Comment;
use App\Form\CommentType;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    #[IsGranted('ROLE_USER')]
    public function index(PostRepository $postRepository): Response
    {
        $posts = $postRepository->findAll();
        $forms = [];

        foreach ($posts as $post) {
            $comment = new Comment();
            $form = $this->createForm(CommentType::class, $comment);
            $forms[$post->getId()] = $form->createView();
        }

        return $this->render('home/index.html.twig', [
            'posts' => $posts,
            'forms' => $forms,
        ]);
    }
}
