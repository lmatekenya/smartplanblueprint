<?php

namespace App\Controller;

use App\Entity\Merchant;
use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    // src/Controller/UserController.php

    /**
     * @throws \Exception
     */
    #[Route('/merchant/portal_users/{id}/user/add', name: 'user_add')]
    public function addUser(Request $request, EntityManagerInterface $em, int $id): Response
    {
        $merchant = $em->getRepository(Merchant::class)->find($id);

        if (!$merchant) {
            throw $this->createNotFoundException('Merchant not found');
        }

        if ($request->isMethod('POST')) {
            // Validate form data
            $firstName = $request->request->get('firstName');
            $lastName = $request->request->get('lastName');
            $email = $request->request->get('email');
            $password = $request->request->get('password');
            $confirmPassword = $request->request->get('confirmPassword');
            $role = $request->request->get('role');
            $enabled = $request->request->has('enabled');

            // Validate passwords match
            if ($password !== $confirmPassword) {
                $this->addFlash('error', 'Passwords do not match');
                return $this->redirectToRoute('portal_users', ['id' => $id]);
            }

            // Check if email already exists
            $existingUser = $em->getRepository(User::class)->findOneBy(['email' => $email]);
            if ($existingUser) {
                $this->addFlash('error', 'Email already in use');
                return $this->redirectToRoute('portal_users', ['id' => $id]);
            }

            // Create new user
            $user = new User();
            $user->setFirstName($firstName);
            $user->setLastName($lastName);
            $user->setEmail($email);
            $user->setPassword(password_hash($password, PASSWORD_BCRYPT));
            $user->setRoles([$role]);
            $user->setEnabled($enabled);
            $user->setMerchant($merchant);
            $user->setDate_created(new \DateTime());

            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'User created successfully');
            return $this->redirectToRoute('portal_users', ['id' => $id]);
        }

        return $this->redirectToRoute('portal_users', ['id' => $id]);
    }


//    #[Route('/merchant/portal_users/{id}/user/{userId}/view', name: 'user_view')]
//    public function view(User $user, int $id, int $userId): Response
//    {
//        return $this->render('merchant/portal_user_add/view.html.twig', [
//            'user' => $user,
//            'merchant' => $user->getMerchant(),
//        ]);
//    }
//
//    #[Route('/merchant/portal_users/{id}/user/{userId}/edit', name: 'user_edit')]
//    public function edit(
//        User $user,
//        Request $request,
//        EntityManagerInterface $em,
//        UserPasswordHasherInterface $passwordHasher,
//        int $id, int $userId
//    ): Response {
//        $form = $this->createForm(UserType::class, $user);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            // Handle password change if provided
//            if ($form->get('plainPassword')->getData()) {
//                $user->setPassword(
//                    $passwordHasher->hashPassword(
//                        $user,
//                        $form->get('plainPassword')->getData()
//                    )
//                );
//            }
//
//            $em->flush();
//            $this->addFlash('success', 'User updated successfully');
//            return $this->redirectToRoute('portal_users', ['id' => $user->getMerchant()->getId()]);
//        }
//
//        return $this->render('merchant/portal_user_add/edit.html.twig', [
//            'form' => $form->createView(),
//            'user' => $user,
//            'merchant' => $user->getMerchant(),
//        ]);
//    }

// src/Controller/UserController.php
    #[Route('/merchant/{id}/user/{userId}/view', name: 'view_user')]
    public function view(int $id, int $userId, EntityManagerInterface $em): Response
    {
        $merchant = $em->getRepository(Merchant::class)->find($id);
        $user = $em->getRepository(User::class)->find($userId);

        if (!$merchant || !$user) {
            throw $this->createNotFoundException('Merchant or User not found');
        }

        return $this->render('merchant/portal_user_add/view.html.twig', [
            'user' => $user,
            'merchant' => $merchant,
        ]);
    }

    #[Route('/merchant/{id}/user/{userId}/edit', name: 'edit_user')]
    public function edit(
        Request $request,
        int $id,
        int $userId,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $passwordHasher
    ): Response {
        $user = $em->getRepository(User::class)->find($userId);
        $merchant = $em->getRepository(Merchant::class)->find($id);

        if (!$user || !$merchant) {
            throw $this->createNotFoundException('User or Merchant not found');
        }

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('plainPassword')->getData()) {
                $user->setPassword(
                    $passwordHasher->hashPassword(
                        $user,
                        $form->get('plainPassword')->getData()
                    )
                );
            }

            $em->flush();
            $this->addFlash('success', 'User updated successfully');
            return $this->redirectToRoute('portal_users', ['id' => $id]);
        }

        return $this->render('merchant/portal_user_add/edit.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
            'merchant' => $merchant,
        ]);
    }
//    #[Route('/merchant/portal_users/{id}/user/{userId}/delete', name: 'user_delete')]
//    public function delete(User $user, EntityManagerInterface $em, Request $request): Response
//    {
//        $merchantId = $user->getMerchant()->getId();
//
//        // Verify CSRF token for security
//        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
//            $em->remove($user);
//            $em->flush();
//            $this->addFlash('success', 'User deleted successfully');
//        } else {
//            $this->addFlash('error', 'Invalid CSRF token');
//        }
//
//        return $this->redirectToRoute('portal_users', ['id' => $merchantId]);
//    }

    #[Route('/merchant/{id}/user/{userId}/delete', name: 'user_delete')]
    public function delete(
        #[MapEntity(id: 'userId')] User $user,
        EntityManagerInterface $em,
        Request $request,
        int $id
    ): Response {
//        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
//            $em->remove($user);
//            $em->flush();
//            $this->addFlash('success', 'User deleted successfully');
//        } else {
//            $this->addFlash('error', 'Invalid CSRF token');
//        }
//
//        return $this->redirectToRoute('portal_users', ['id' => $id]);

        if (!$this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $this->addFlash('error', 'Invalid CSRF token');
            return $this->redirectToRoute('portal_users', ['id' => $id]);
        }

        // Verify user belongs to merchant
        if ($user->getMerchant()->getId() !== $id) {
            throw $this->createAccessDeniedException('User does not belong to this merchant');
        }

        $em->remove($user);
        $em->flush();
        $this->addFlash('success', 'User deleted successfully');

        return $this->redirectToRoute('portal_users', ['id' => $id]);
    }

}
