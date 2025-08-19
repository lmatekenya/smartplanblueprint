<?php
//
//namespace App\Controller\PortalUser;
//
//use App\Entity\Merchant;
//use App\Repository\Merchant\PortalUserRepository;
//use App\Repository\MerchantRepository;
//use App\Repository\UserRepository;
//use Symfony\Component\HttpFoundation\Response;
//use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
//use Symfony\Component\Routing\Annotation\Route;
//#[Route('/merchant')]
//class PortalUserController extends AbstractController
//{
//    #[Route('/portal_users/{id}', name: 'portal_users')]
//    public function portalUsers(
//        Merchant $merchant,
//        PortalUserRepository $portalUserRepository
//    ): Response
//    {
//        $portalUsers = $portalUserRepository->findByMerchantWithDetails($merchant->getId());
//
//        return $this->render('merchant/portal_users.html.twig', [
//            'merchant' => $merchant,
//            'portalUsers' => $portalUsers
//        ]);
//    }
//}
//
//


namespace App\Controller\PortalUser;

use App\Entity\Merchant;

use App\Form\PortalUserType;
use App\Repository\Merchant\PortalUserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/merchant')]
class PortalUserController extends AbstractController
{
    #[Route('/portal_users/{id}', name: 'portal_users')]
    public function portalUsers(
        Merchant $merchant,
        PortalUserRepository $portalUserRepository
    ): Response {
        $portalUsers = $portalUserRepository->findByMerchantWithDetails($merchant->getId());

        return $this->render('merchant/portal_users.html.twig', [
            'merchant' => $merchant,
            'portalUsers' => $portalUsers
        ]);
    }

//    #[Route('/edit_user/{id}/{userId}', name: 'edit_user', methods: ['GET', 'POST'])]
//    public function editUser(
//        Merchant $merchant,
//        int $userId,
//        Request $request,
//        PortalUserRepository $portalUserRepository,
//        EntityManagerInterface $entityManager
//    ): Response {
//        $this->denyAccessUnlessGranted('ROLE_ADMIN');
//
//        $portalUser = $portalUserRepository->find($userId);
//
//        if (!$portalUser) {
//            throw $this->createNotFoundException('User not found');
//        }
//
//        if ($portalUser->getMerchant()->getId() !== $merchant->getId()) {
//            throw $this->createAccessDeniedException('You cannot modify users from other merchants');
//        }
//
//        $form = $this->createForm(PortalUserType::class, $portalUser);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $entityManager->flush();
//
//            $this->addFlash(
//                'success',
//                sprintf('User %s has been updated', $portalUser->getEmail())
//            );
//
//            return $this->redirectToRoute('portal_users', ['id' => $merchant->getId()]);
//        }
//
//        return $this->render('merchant/portal_user_add/edit.html.twig', [
//            'merchant' => $merchant,
//            'user' => $portalUser,
//            'form' => $form->createView(),
//        ]);
//    }

    #[Route('/edit_user/{id}/{userId}', name: 'edit_user', methods: ['GET', 'POST'])]
    public function editUser(
        Merchant $merchant,
        int $userId,
        Request $request,
        PortalUserRepository $portalUserRepository,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $portalUser = $portalUserRepository->find($userId);

        if (!$portalUser) {
            throw $this->createNotFoundException('User not found');
        }

        if ($portalUser->getMerchant()->getId() !== $merchant->getId()) {
            throw $this->createAccessDeniedException('You cannot modify users from other merchants');
        }



        $form = $this->createForm(PortalUserType::class, $portalUser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Handle password change
            $plainPassword = $form->get('plainPassword')->getData();
            if ($plainPassword) {
                $portalUser->setPassword($passwordHasher->hashPassword($portalUser, $plainPassword));
            }

            // Handle active status
            $isActive = $form->get('isActive')->getData();
            $portalUser->setEnabled($isActive);

            $entityManager->flush();

            $this->addFlash(
                'success',
                sprintf('User %s has been updated', $portalUser->getEmail())
            );

            return $this->redirectToRoute('portal_users', ['id' => $merchant->getId()]);
        }

        return $this->render('merchant/portal_user_add/edit.html.twig', [
            'merchant' => $merchant,
            'user' => $portalUser,
            'form' => $form->createView(),
        ]);
    }
}
