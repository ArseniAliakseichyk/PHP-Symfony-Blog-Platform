<?php

namespace System\Modules\Main;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use System\Modules\Article\ArticleGateway;
use System\Modules\Feedback\Feedback;
use System\Modules\Feedback\Form\FeedbackForm;


#[Route('/', name: 'main_')]
class MainController extends AbstractController
{
    #[Route('', name: 'page')]
    #[IsGranted('ROLE_USER')]
    public function index(ArticleGateway $articleGateway): Response
    {
        $articles = $articleGateway->findAll();
        $forms = [];

        foreach ($articles as $article) {
            $feedback = new Feedback();
            $form = $this->createForm(FeedbackForm::class, $feedback);
            $forms[$article->getId()] = $form->createView();
        }

        return $this->render('main/index.html.twig', [
            'articles' => $articles,
            'forms' => $forms,
        ]);
    }
}
