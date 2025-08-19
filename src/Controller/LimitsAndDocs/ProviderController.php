<?php

namespace App\Controller\LimitsAndDocs;

use App\Entity\LimitsAndDocs\Provider;
use App\Form\ProviderType;

use App\Repository\LimitsAndDocs\ProviderRepository;
use App\Repository\MerchantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/merchant')]
class ProviderController extends AbstractController
{
    #[Route('/providers/{id}', name: 'providers')]
    public function provider(
        int $id,
        MerchantRepository $merchantRepository,
        ProviderRepository $providerRepository
    ): Response {
        $merchant = $merchantRepository->find($id);

        if (!$merchant) {
            throw $this->createNotFoundException('Merchant not found');
        }

        $providers = $providerRepository->findByMerchant($id);

        return $this->render('limits_and_documents/providers.html.twig', [
            'merchant' => $merchant,
            'providers' => $providers
        ]);
    }

    #[Route('/providers/{id}/new', name: 'provider_new')]
    public function new_provider(
        int $id,
        Request $request,
        MerchantRepository $merchantRepository,
        EntityManagerInterface $entityManager
    ): Response {
        $merchant = $merchantRepository->find($id);
        $provider = new Provider();
        $provider->setMerchant($merchant);
        $provider->setDateCreated(new \DateTime());

        $form = $this->createForm(ProviderType::class, $provider);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($provider);
            $entityManager->flush();

            return $this->redirectToRoute('providers', ['id' => $id]);
        }

        return $this->render('limits_and_documents/provider_form.html.twig', [
            'form' => $form->createView(),
            'merchant' => $merchant
        ]);
    }

    #[Route('/providers/{id}/edit/{providerId}', name: 'provider_edit')]
    public function edit_provider(
        int $id,
        int $providerId,
        Request $request,
        ProviderRepository $providerRepository,
        EntityManagerInterface $entityManager
    ): Response {
        $provider = $providerRepository->find($providerId);

        if (!$provider) {
            throw $this->createNotFoundException('Provider not found');
        }

        $form = $this->createForm(ProviderType::class, $provider);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('providers', ['id' => $id]);
        }

        return $this->render('limits_and_documents/provider/provider_edit.html.twig', [
            'form' => $form->createView(),
            'provider' => $provider,
            'merchant' => $provider->getMerchant()
        ]);
    }

//    #[Route('/providers/{id}/delete/{providerId}', name: 'provider_delete')]
//    public function delete_provider(
//        int $id,
//        int $providerId,
//        Request $request,
//        ProviderRepository $providerRepository,
//        EntityManagerInterface $entityManager
//    ): Response {
//        $provider = $providerRepository->find($providerId);
//
////        if ($provider) {
////            $entityManager->remove($provider);
////            $entityManager->flush();
////        }
////
////        return $this->redirectToRoute('providers', ['id' => $id]);
//
//        if (!$provider) {
//            $this->addFlash('error', 'Provider not found');
//            return $this->redirectToRoute('providers', ['id' => $id]);
//        }
//
//        if ($request->isMethod('POST')) {
//            $entityManager->remove($provider);
//            $entityManager->flush();
//
//            $this->addFlash('success', 'Provider deleted successfully');
//            return $this->redirectToRoute('providers', ['id' => $id]);
//        }
//
//        return $this->render('limits_and_documents/provider/provider_delete.html.twig', [
//            'provider' => $provider,
//            'merchant' => $provider->getMerchant(),
//            'id' => $id,
//            'providerId' => $providerId,
//        ]);
//    }
}
