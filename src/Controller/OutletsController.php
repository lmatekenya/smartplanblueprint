<?php

namespace App\Controller;


use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Csrf\CsrfToken;


class OutletsController extends AbstractController
{
// src/Controller/UserController.php
    #[Route('/merchant/outlet', name: 'outlet_display')]
    public function add(): Response { /* ... */
        return $this->render('merchant/outlet.html.twig');
    }

    #[Route('/edit', name: 'outlet_edit')]
    public function view(int $id): Response { /* ... */
        return $this->render('merchant/outlet.html.twig');}

    #[Route('/documents', name: 'outlet_documents')]
    public function edit(int $id): Response { /* ... */
        return $this->render('merchant/outlet.html.twig');}

    #[Route('/limits', name: 'outlet_limits')]
    public function delete(int $id): Response { /* ... */
        return $this->render('merchant/outlet.html.twig');}

    /**
     * @Route("/outlet/users/{id}/toggle", name="outlet_user_toggle", methods={"POST"})
     */
    #[Route('/outlet/users/{id}/toggle', name: 'outlet_user_toggle', methods: ['POST'])]
    public function toggleUserStatus(
        Request $request,
    int $id,
    CsrfTokenManagerInterface $csrfTokenManager
): JsonResponse {
        // Validate CSRF token
        $token = new CsrfToken('toggle-user', $request->request->get('_token'));
        if (!$csrfTokenManager->isTokenValid($token)) {
            return new JsonResponse(['error' => 'Invalid CSRF token'], 403);
        }


        $entityManager = $this->getDoctrine()->getManager();
        $user = $entityManager->getRepository(User::class)->find($id);

        if (!$user) {
            return new JsonResponse(['error' => 'User not found'], 404);
        }

        // Toggle the enabled status
        $user->setEnabled(!$user->isEnabled());
        $entityManager->flush();

        return new JsonResponse([
            'success' => true,
            'enabled' => $user->isEnabled()
        ]);
    }
}
