<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
public function register(
    Request $request,
    UserPasswordHasherInterface $passwordHasher,
    EntityManagerInterface $entityManager
): Response {
    $user = new User();
    $form = $this->createForm(RegistrationFormType::class, $user);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        if (empty(array_intersect($user->getRoles(), ['ROLE_USER', 'ROLE_ADMIN']))) {
            $user->setRoles(array_merge($user->getRoles(), ['ROLE_USER']));
        }
        $user->setPassword(
            $passwordHasher->hashPassword(
                $user,
                $form->get('plainPassword')->getData()
            )
        );

        $entityManager->persist($user);
        $entityManager->flush();

        $this->addFlash('success', 'Registration successful!');
        return $this->redirectToRoute('home');
    }

    return $this->render('registration/register.html.twig', [
        'registrationForm' => $form->createView(),
    ]);
}
}