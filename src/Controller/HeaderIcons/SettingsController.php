<?php
//// src/Controller/HeaderIcons/SettingsController.php
//namespace App\Controller\HeaderIcons;
//
//use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
//use Symfony\Component\HttpFoundation\JsonResponse;
//use Symfony\Component\HttpFoundation\Request;
//use Symfony\Component\HttpFoundation\Response;
//use Symfony\Component\Routing\Annotation\Route;
//
//class SettingsController extends AbstractController
//{
//    #[Route('/settings/profile', name: 'app_settings_profile')]
//    public function profile(): Response
//    {
//        return $this->render('header_icons/settings/profile.html.twig');
//    }
//
//    #[Route('/settings/notifications', name: 'app_settings_notifications')]
//    public function notifications(): Response
//    {
//        return $this->render('header_icons/settings/notifications.html.twig');
//    }
//
//    #[Route('/settings/security', name: 'app_settings_security')]
//    public function security(): Response
//    {
//        return $this->render('header_icons/settings/security.html.twig');
//    }
//
//    #[Route('/settings/theme', name: 'app_settings_theme', methods: ['POST'])]
//    public function changeTheme(Request $request): JsonResponse
//    {
//        $theme = $request->request->get('theme', 'auto');
//        $request->getSession()->set('theme', $theme);
//
//        return $this->json(['success' => true]);
//    }
//}


// src/Controller/HeaderIcons/SettingsController.php
namespace App\Controller\HeaderIcons;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SettingsController extends AbstractController
{
    #[Route('/settings/profile', name: 'app_settings_profile')]
    public function profile(Request $request, EntityManagerInterface $em): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        // Create a simple form
        $form = $this->createFormBuilder($user)
            ->add('firstName', TextType::class, [
                'label' => 'First Name',
                'required' => true
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Last Name',
                'required' => true
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email Address',
                'required' => true
            ])
            ->add('phone', TelType::class, [
                'label' => 'Phone Number',
                'required' => false
            ])
            ->add('save', SubmitType::class, ['label' => 'Save Changes'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Profile updated successfully');
            return $this->redirectToRoute('app_settings_profile');
        }

        return $this->render('header_icons/settings/profile.html.twig', [
            'user' => $user,
            'form' => $form->createView()
        ]);
    }

    #[Route('/settings/notifications', name: 'app_settings_notifications')]
    public function notifications(): Response
    {
        return $this->render('header_icons/settings/notifications.html.twig');
    }

    #[Route('/settings/security', name: 'app_settings_security')]
    public function security(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        // Create password change form
        $form = $this->createFormBuilder()
            ->add('currentPassword', PasswordType::class, [
                'label' => 'Current Password',
                'required' => true,
                'mapped' => false
            ])
            ->add('newPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'required' => true,
                'first_options' => ['label' => 'New Password'],
                'second_options' => ['label' => 'Repeat New Password'],
                'mapped' => false
            ])
            ->add('save', SubmitType::class, ['label' => 'Update Password'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            // Verify current password
            if (!$passwordHasher->isPasswordValid($user, $data['currentPassword'])) {
                $this->addFlash('error', 'Current password is incorrect');
                return $this->redirectToRoute('app_settings_security');
            }

            // Update password
            $user->setPassword(
                $passwordHasher->hashPassword($user, $data['newPassword'])
            );

            $em->flush();
            $this->addFlash('success', 'Password updated successfully');
            return $this->redirectToRoute('app_settings_security');
        }

        return $this->render('header_icons/settings/security.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/settings/theme', name: 'app_settings_theme', methods: ['POST'])]
    public function changeTheme(Request $request): JsonResponse
    {
        $theme = $request->request->get('theme', 'auto');
        $request->getSession()->set('theme', $theme);

        return $this->json(['success' => true]);
    }
}
