<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class AdminController extends AbstractController
{
    #[Route('/admin/users', name: 'admin_users')]
    #[IsGranted('ROLE_ADMIN')]
    public function users(UserRepository $userRepository): Response
    {
        return $this->render('admin/users.html.twig', [
            'users' => $userRepository->findAll()
        ]);
    }

    #[Route('/admin/users/{id}/delete', name: 'admin_user_delete', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function deleteUser(
        Request $request,
        User $user,
        EntityManagerInterface $entityManager
    ): Response {
        if ($user === $this->getUser()) {
            $this->addFlash('danger', 'You cannot delete yourself!');
            return $this->redirectToRoute('admin_users');
        }

        $csrfToken = $request->request->get('_token');
        if (!$this->isCsrfTokenValid('delete-user' . $user->getId(), $csrfToken)) {
            throw $this->createAccessDeniedException('Invalid CSRF token');
        }

        $entityManager->remove($user);
        $entityManager->flush();

        $this->addFlash('success', 'User deleted successfully');
        return $this->redirectToRoute('admin_users');
    }
}
