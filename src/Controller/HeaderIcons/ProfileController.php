<?php
//
//namespace App\Controller\HeaderIcons;
//
//
//use App\Entity\User;
//use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
//use Symfony\Component\HttpFoundation\JsonResponse;
//use Symfony\Component\HttpFoundation\Request;
//use Symfony\Component\Routing\Annotation\Route;
//
//
//class ProfileController extends AbstractController
//{
//    /**
//     * @Route("/api/profile", name="api_profile", methods={"GET"})
//     */
//
//    #[Route('/api/profile', name: 'api_profile', methods: ['GET'])]
//    public function getProfile(): JsonResponse
//    {
//        $user = $this->getUser();
//
//        return $this->json([
//            'id' => $user->getId(),
//            'email' => $user->getEmail(),
//            'firstName' => $user->getFirstName(),
//            'lastName' => $user->getLastName(),
//            'roles' => $user->getRoles(),
//            'createdAt' => $user->getCreatedAt(),
//        ]);
//    }
//
//    /**
//     * @Route("/api/profile", name="api_profile_update", methods={"PUT"})
//     */
//    #[Route('/api/profile', name: 'api_profile_update', methods: ['PUT'])]
//    public function updateProfile(Request $request): JsonResponse
//    {
//        $user = $this->getUser();
//        $data = json_decode($request->getContent(), true);
//
//        if (isset($data['firstName'])) {
//            $user->setFirstName($data['firstName']);
//        }
//
//        if (isset($data['lastName'])) {
//            $user->setLastName($data['lastName']);
//        }
//
//        if (isset($data['email'])) {
//            $user->setEmail($data['email']);
//        }
//
//        $entityManager = $this->getDoctrine()->getManager();
//        $entityManager->persist($user);
//        $entityManager->flush();
//
//        return $this->json(['success' => true, 'user' => [
//            'firstName' => $user->getFirstName(),
//            'lastName' => $user->getLastName(),
//            'email' => $user->getEmail()
//        ]]);
//    }
//
//    /**
//     * @Route("/api/profile/change-password", name="api_profile_change_password", methods={"POST"})
//     */
//
//    #[Route('/api/profile/change-password', name: 'api_profile_change_password', methods: ['POST'])]
//    public function changePassword(Request $request, UserPasswordEncoderInterface $passwordEncoder): JsonResponse
//    {
//        $user = $this->getUser();
//        $data = json_decode($request->getContent(), true);
//
//        if (!isset($data['currentPassword']) || !isset($data['newPassword'])) {
//            return $this->json(['error' => 'Current password and new password are required'], 400);
//        }
//
//        // Check current password
//        if (!$passwordEncoder->isPasswordValid($user, $data['currentPassword'])) {
//            return $this->json(['error' => 'Current password is incorrect'], 400);
//        }
//
//        // Encode new password
//        $encodedPassword = $passwordEncoder->encodePassword($user, $data['newPassword']);
//        $user->setPassword($encodedPassword);
//
//        $entityManager = $this->getDoctrine()->getManager();
//        $entityManager->persist($user);
//        $entityManager->flush();
//
//        return $this->json(['success' => true]);
//    }
//}


// src/Controller/HeaderIcons/ProfileController.php
namespace App\Controller\HeaderIcons;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile_view')]
    public function view(): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        return $this->render('header_icons/profile/view.html.twig', [
            'user' => $user
        ]);
    }

    #[Route('/profile/edit', name: 'app_profile_edit')]
    public function edit(Request $request, EntityManagerInterface $em): Response
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
            return $this->redirectToRoute('app_profile_view');
        }

        return $this->render('header_icons/profile/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView()
        ]);
    }
}
