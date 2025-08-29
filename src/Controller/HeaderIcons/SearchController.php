<?php
//
//namespace App\Controller\HeaderIcons;
//
//use App\Entity\HeaderIcons\SearchHistory;
//use App\Repository\HeaderIcons\SearchHistoryRepository;
//use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
//use Symfony\Component\HttpFoundation\JsonResponse;
//use Symfony\Component\HttpFoundation\Request;
//use Symfony\Component\Routing\Annotation\Route;
//
//class SearchController extends AbstractController
//{
//    /**
//     * @Route("/api/search", name="api_search", methods={"POST"})
//     */
//    public function search(Request $request, SearchHistoryRepository $searchHistoryRepo): JsonResponse
//    {
//        $user = $this->getUser();
//        $data = json_decode($request->getContent(), true);
//        $query = $data['query'] ?? '';
//
//        if (empty($query)) {
//            return $this->json(['error' => 'Search query is required'], 400);
//        }
//
//        // Save search history
//        $searchHistory = new SearchHistory();
//        $searchHistory->setUser($user);
//        $searchHistory->setQuery($query);
//        $searchHistory->setCreatedAt(new \DateTime());
//
//        $entityManager = $this->getDoctrine()->getManager();
//        $entityManager->persist($searchHistory);
//        $entityManager->flush();
//
//        // Perform search across different entities
//        $results = [
//            'merchants' => $this->searchMerchants($query),
//            'transactions' => $this->searchTransactions($query),
//            'users' => $this->searchUsers($query),
//        ];
//
//        return $this->json(['results' => $results]);
//    }
//
//    /**
//     * @Route("/api/search/history", name="api_search_history", methods={"GET"})
//     */
//    public function getSearchHistory(SearchHistoryRepository $searchHistoryRepo): JsonResponse
//    {
//        $user = $this->getUser();
//        $history = $searchHistoryRepo->findBy(
//            ['user' => $user],
//            ['createdAt' => 'DESC'],
//            10
//        );
//
//        return $this->json(['history' => $history]);
//    }
//
//    private function searchMerchants(string $query): array
//    {
//        // Implement merchant search logic
//        return [];
//    }
//
//    private function searchTransactions(string $query): array
//    {
//        // Implement transaction search logic
//        return [];
//    }
//
//    private function searchUsers(string $query): array
//    {
//        // Implement user search logic
//        return [];
//    }
//}


namespace App\Controller\HeaderIcons;

use App\Entity\HeaderIcons\SearchHistory;
use App\Repository\HeaderIcons\SearchHistoryRepository;
use App\Repository\MerchantRepository;
use App\Repository\TransactionRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    /**
     * @Route("/api/search", name="api_search", methods={"POST"})
     */
    #[Route('/api/search', name: 'api_search', methods: ['POST'])]
    public function search(
        Request                 $request,
        SearchHistoryRepository $searchHistoryRepo,
        MerchantRepository      $merchantRepo,
        TransactionRepository   $transactionRepo,
        UserRepository          $userRepo
    ): JsonResponse
    {
        $user = $this->getUser();
        $data = json_decode($request->getContent(), true);
        $query = $data['query'] ?? '';

        if (empty($query)) {
            return $this->json(['error' => 'Search query is required'], 400);
        }

        // Save search history
        $searchHistory = new SearchHistory();
        $searchHistory->setUser($user);
        $searchHistory->setQuery($query);
        $searchHistory->setCreatedAt(new \DateTime());

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($searchHistory);
        $entityManager->flush();

        // Perform search across different entities
        $results = [
            'merchants' => $merchantRepo->search($query),
            'transactions' => $transactionRepo->search($query),
            'users' => $userRepo->search($query),
        ];

        return $this->json(['results' => $results]);
    }

    /**
     * @Route("/api/search/history", name="api_search_history", methods={"GET"})
     */
    #[Route('/api/search/history', name: 'api_search_history', methods: ['GET'])]
    public function getSearchHistory(SearchHistoryRepository $searchHistoryRepo): JsonResponse
    {
        $user = $this->getUser();
        $history = $searchHistoryRepo->findBy(
            ['user' => $user],
            ['createdAt' => 'DESC'],
            10
        );

        $formattedHistory = array_map(function ($item) {
            return [
                'id' => $item->getId(),
                'query' => $item->getQuery(),
                'createdAt' => $item->getCreatedAt()->format('Y-m-d H:i:s')
            ];
        }, $history);

        return $this->json(['history' => $formattedHistory]);
    }

    /**
     * @Route("/api/search/history/{id}", name="api_search_history_delete", methods={"DELETE"})
     */
    #[Route('/api/search/history/{id}', name: 'api_search_history_delete', methods: ['DELETE'])]
    public function deleteSearchHistory(SearchHistory $searchHistory): JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($searchHistory);
        $entityManager->flush();

        return $this->json(['message' => 'Search history item deleted']);
    }

    /**
     * @Route("/search/results", name="app_search_results")
     */
    #[Route('/search/results', name: 'app_search_results')]
    public function searchResults(): Response
    {
        return $this->render('header_icons/search/search_results.html.twig');
    }
}

//namespace App\Controller\HeaderIcons;
//
//use App\Entity\HeaderIcons\SearchHistory;
//use App\Repository\HeaderIcons\SearchHistoryRepository;
//use App\Repository\MerchantRepository;
//use App\Repository\TransactionRepository;
//use App\Repository\UserRepository;
//use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
//use Symfony\Component\HttpFoundation\JsonResponse;
//use Symfony\Component\HttpFoundation\Request;
//use Symfony\Component\Routing\Annotation\Route;
//
//class SearchController extends AbstractController
//{
//    /**
//     * @Route("/api/search", name="api_search", methods={"POST"})
//     */
//    public function search(
//        Request                 $request,
//        SearchHistoryRepository $searchHistoryRepo,
//        MerchantRepository      $merchantRepo,
//        TransactionRepository   $transactionRepo,
//        UserRepository          $userRepo
//    ): JsonResponse
//    {
//        $user = $this->getUser();
//        $data = json_decode($request->getContent(), true);
//        $query = $data['query'] ?? '';
//
//        if (empty($query)) {
//            return $this->json(['error' => 'Search query is required'], 400);
//        }
//
//        // Save search history
//        $searchHistory = new SearchHistory();
//        $searchHistory->setUser($user);
//        $searchHistory->setQuery($query);
//        $searchHistory->setCreatedAt(new \DateTime());
//
//        $entityManager = $this->getDoctrine()->getManager();
//        $entityManager->persist($searchHistory);
//        $entityManager->flush();
//
//        // Perform search across different entities
//        $results = [
//            'merchants' => $merchantRepo->search($query),
//            'transactions' => $transactionRepo->search($query),
//            'users' => $userRepo->search($query),
//        ];
//
//        return $this->json(['results' => $results]);
//    }
//
//    /**
//     * @Route("/api/search/history", name="api_search_history", methods={"GET"})
//     */
//    public function getSearchHistory(SearchHistoryRepository $searchHistoryRepo): JsonResponse
//    {
//        $user = $this->getUser();
//        $history = $searchHistoryRepo->findBy(
//            ['user' => $user],
//            ['createdAt' => 'DESC'],
//            10
//        );
//
//        $formattedHistory = array_map(function ($item) {
//            return [
//                'id' => $item->getId(),
//                'query' => $item->getQuery(),
//                'createdAt' => $item->getCreatedAt()->format('Y-m-d H:i:s')
//            ];
//        }, $history);
//
//        return $this->json(['history' => $formattedHistory]);
//    }
//
//    /**
//     * @Route("/api/search/history/{id}", name="api_search_history_delete", methods={"DELETE"})
//     */
//    public function deleteSearchHistory(SearchHistory $searchHistory): JsonResponse
//    {
//        $entityManager = $this->getDoctrine()->getManager();
//        $entityManager->remove($searchHistory);
//        $entityManager->flush();
//
//        return $this->json(['message' => 'Search history item deleted']);
//    }
//}
