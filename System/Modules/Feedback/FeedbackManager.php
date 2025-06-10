<?php

namespace System\Modules\Feedback;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use System\Modules\Article\Article;
use System\Modules\Feedback\Form\FeedbackForm;
use Symfony\Component\Form\Extension\Core\Type\FormType;

#[Route('/feedback')]
class FeedbackManager extends AbstractController
{
    #[Route('/{article}/create', name: 'feedback_create', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function create(Request $request, Article $article, FeedbackGateway $feedbackGateway): Response
    {
        $feedback = new Feedback();
        $form = $this->createForm(FeedbackForm::class, $feedback);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $feedback->setAuthor($this->getUser());
            $feedback->setArticle($article);

            $feedbackGateway->save($feedback);
            $this->addFlash('success', 'Feedback submitted successfully!');
            return $this->redirectToRoute('main_page');
        }

        $this->addFlash('error', 'Error submitting feedback');
        return $this->redirectToRoute('main_page');
    }

    #[Route('/{id}/modify', name: 'feedback_modify', methods: ['GET', 'POST'])]
    #[IsGranted('FEEDBACK_MODIFY', subject: 'feedback')]
    public function modify(Request $request, Feedback $feedback, FeedbackGateway $feedbackGateway): Response
    {
        $form = $this->createForm(FeedbackForm::class, $feedback);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $feedbackGateway->save($feedback);
            $this->addFlash('success', 'Feedback updated successfully!');
            return $this->redirectToRoute('main_page');
        }

        $deleteForm = $this->createFormBuilder()
            ->setAction($this->generateUrl('feedback_remove', ['id' => $feedback->getId()]))
            ->setMethod('POST')
            ->getForm();

        return $this->render('feedback/modify.html.twig', [
            'feedbackForm' => $form->createView(),
            'deleteForm' => $deleteForm->createView(),
            'feedback' => $feedback,
        ]);
    }

    #[Route('/{id}/remove', name: 'feedback_remove', methods: ['POST'])]
    #[IsGranted('FEEDBACK_REMOVE', subject: 'feedback')]
    public function remove(Request $request, Feedback $feedback, FeedbackGateway $feedbackGateway): Response
    {
        $csrfToken = $request->request->get('_token');
        if (!$this->isCsrfTokenValid('remove_feedback' . $feedback->getId(), $csrfToken)) {
            throw $this->createAccessDeniedException('Invalid CSRF token');
        }

        $feedbackGateway->remove($feedback);
        $this->addFlash('success', 'Feedback removed successfully!');
        return $this->redirectToRoute('main_page');
    }
}
