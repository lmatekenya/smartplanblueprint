<?php

namespace App\Controller;

// src/Controller/SecurityController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route('/login', name: 'app_login', methods: ['GET', 'POST'])]
    public function login(Request $request, AuthenticationUtils $authenticationUtils): Response
    {

        // Debug form submission
        if ($request->isMethod('POST')) {
            dump([
                'submitted_email' => $request->request->get('email'),
                'submitted_password' => $request->request->get('password'),
                'server_users' => $this->getParameter('security.user.providers.in_memory.memory.users')
            ]);
            return $this->redirectToRoute('app_dashboard');
        }

        if ($this->getUser()) {
            return $this->redirectToRoute('app_dashboard');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
          //  'csrf_token' => null, // Handled automatically by Symfony
        ]);
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout(): Response
    {

//        return $this->render('security/login.html.twig');
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
