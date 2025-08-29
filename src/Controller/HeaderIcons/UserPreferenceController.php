<?php

namespace App\Controller\HeaderIcons;


use App\Entity\HeaderIcons\UserPreference;
use App\Repository\HeaderIcons\UserPreferenceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserPreferenceController extends AbstractController
{
    /**
     * @Route("/api/preferences", name="api_preferences", methods={"GET"})
     */
    public function getPreferences(UserPreferenceRepository $preferenceRepo): JsonResponse
    {
        $user = $this->getUser();
        $preferences = $preferenceRepo->findBy(['user' => $user]);

        $preferencesArray = [];
        foreach ($preferences as $preference) {
            $preferencesArray[$preference->getKey()] = $preference->getValue();
        }

        return $this->json(['preferences' => $preferencesArray]);
    }

    /**
     * @Route("/api/preferences", name="api_preferences_save", methods={"POST"})
     */
    public function savePreference(Request $request, UserPreferenceRepository $preferenceRepo): JsonResponse
    {
        $user = $this->getUser();
        $data = json_decode($request->getContent(), true);

        if (!isset($data['key']) || !isset($data['value'])) {
            return $this->json(['error' => 'Key and value are required'], 400);
        }

        $preference = $preferenceRepo->findOneBy([
            'user' => $user,
            'key' => $data['key']
        ]);

        $entityManager = $this->getDoctrine()->getManager();

        if (!$preference) {
            $preference = new UserPreference();
            $preference->setUser($user);
            $preference->setKey($data['key']);
        }

        $preference->setValue($data['value']);
        $preference->setUpdatedAt(new \DateTime());

        $entityManager->persist($preference);
        $entityManager->flush();

        return $this->json(['success' => true, 'preference' => [
            'key' => $preference->getKey(),
            'value' => $preference->getValue()
        ]]);
    }
}
