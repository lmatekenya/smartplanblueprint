<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
// src/Controller/UserController.php
    #[Route('/user/add', name: 'user_add')]
    public function add(): Response { /* ... */
        return $this->render('merchant/portal_users.html.twig');
    }

    #[Route('/user/{id}', name: 'user_view')]
    public function view(int $id): Response { /* ... */
        return $this->render('merchant/portal_users.html.twig');}

    #[Route('/user/{id}/edit', name: 'user_edit')]
    public function edit(int $id): Response { /* ... */
        return $this->render('merchant/portal_users.html.twig');}

    #[Route('/user/{id}/delete', name: 'user_delete')]
    public function delete(int $id): Response { /* ... */
        return $this->render('merchant/portal_users.html.twig');}

}
