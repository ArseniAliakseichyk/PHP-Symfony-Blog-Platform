<?php

namespace System\Modules\Article;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use System\Modules\Article\Form\ArticleForm;

#[Route('/content')]
class ContentManager extends AbstractController
{
    #[Route('/submit', name: 'content_create')]
    #[IsGranted('ROLE_USER')]
    public function create(Request $request, ArticleGateway $gateway): Response
    {
        $article = new Article();
        $article->setAuthor($this->getUser());

        $form = $this->createForm(ArticleForm::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $gateway->save($article);
            $this->addFlash('success', 'Article created successfully!');
            return $this->redirectToRoute('main_page');
        }

        return $this->render('article/create.html.twig', [
            'contentForm' => $form->createView(),
        ]);
    }

    #[Route('/{id}/modify', name: 'content_modify', methods: ['GET', 'POST'])]
    #[IsGranted('ARTICLE_MODIFY', subject: 'article')]
    #[Route('/{id}/modify', name: 'content_modify', methods: ['GET', 'POST'])]
    #[IsGranted('ARTICLE_MODIFY', subject: 'article')]
    public function modify(Request $request, Article $article, ArticleGateway $gateway): Response
    {
        $form = $this->createForm(ArticleForm::class, $article);
        $form->handleRequest($request);

        $deleteForm = $this->createFormBuilder()
            ->setAction($this->generateUrl('content_remove', ['id' => $article->getId()]))
            ->setMethod('POST')
            ->getForm();

        if ($form->isSubmitted() && $form->isValid()) {
            $gateway->save($article);
            $this->addFlash('success', 'Article updated successfully!');
            return $this->redirectToRoute('main_page');
        }

        return $this->render('article/modify.html.twig', [
            'contentForm' => $form->createView(),
            'article' => $article,
            'deleteForm' => $deleteForm->createView(),
        ]);
    }


    #[Route('/{id}/remove', name: 'content_remove', methods: ['POST'])]
    #[IsGranted('ARTICLE_REMOVE', subject: 'article')]
    public function remove(Request $request, Article $article, ArticleGateway $gateway): Response
    {
        $csrfToken = $request->request->get('_token');
        if (!$this->isCsrfTokenValid('remove_article' . $article->getId(), $csrfToken)) {
            throw $this->createAccessDeniedException('Invalid CSRF token');
        }

        $gateway->remove($article);
        $this->addFlash('success', 'Article removed successfully!');
        return $this->redirectToRoute('main_page');
    }
}
