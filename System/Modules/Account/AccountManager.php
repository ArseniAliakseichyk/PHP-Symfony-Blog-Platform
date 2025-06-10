<?php

namespace System\Modules\Account;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Form\Extension\Core\Type\FormType;

#[Route('/account')]
class AccountManager extends AbstractController
{

    #[Route('/manage', name: 'account_manage')]
    #[IsGranted('ROLE_ADMIN')]
    public function manage(AccountGateway $gateway): Response
    {
        $deleteForm = $this->createForm(FormType::class);

        return $this->render('account/manage.html.twig', [
            'accounts' => $gateway->findAll(),
            'deleteForm' => $deleteForm->createView(),
        ]);
    }

    #[Route('/{id}/remove', name: 'account_remove', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function remove(Request $request, Account $account, AccountGateway $gateway): Response
    {
        if ($account === $this->getUser()) {
            $this->addFlash('error', 'You cannot remove yourself!');
            return $this->redirectToRoute('account_manage');
        }

        $csrfToken = $request->request->get('_token');
        if (!$this->isCsrfTokenValid('remove_account' . $account->getId(), $csrfToken)) {
            throw $this->createAccessDeniedException('Invalid CSRF token');
        }

        $gateway->remove($account);
        $this->addFlash('success', 'Account removed successfully');
        return $this->redirectToRoute('account_manage');
    }
}
