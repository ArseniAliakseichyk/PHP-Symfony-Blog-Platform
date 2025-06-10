<?php

namespace System\Modules\Account;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use System\Modules\Account\Form\RegistrationForm;

#[Route('/signup')]
class AccountRegistrationController extends AbstractController
{
    #[Route('', name: 'account_signup')]
    public function signup(Request $request, UserPasswordHasherInterface $passwordHasher, AccountGateway $gateway): Response
    {
        $account = new Account();
        $form = $this->createForm(RegistrationForm::class, $account);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (empty(array_intersect($account->getRoles(), ['ROLE_USER', 'ROLE_ADMIN']))) {
                $account->setRoles(array_merge($account->getRoles(), ['ROLE_USER']));
            }
            $account->updateCredentials(
                $passwordHasher->hashPassword(
                    $account,
                    $form->get('plainPassword')->getData()
                )
            );

            $gateway->save($account);
            $this->addFlash('success', 'Account created successfully!');
            return $this->redirectToRoute('main_page');
        }

        return $this->render('account/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
