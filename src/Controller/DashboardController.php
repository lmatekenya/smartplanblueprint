<?php
//// src/Controller/DashboardController.php
//namespace App\Controller;
//
//use App\Entity\Merchant;
//use App\Repository\MerchantRepository;
//use App\Repository\TransactionRepository;
//use App\Service\DashboardDataService;
//use Doctrine\ORM\EntityManagerInterface;
//use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
//use Symfony\Component\HttpFoundation\Request;
//use Symfony\Component\HttpFoundation\Response;
//use Symfony\Component\Routing\Annotation\Route;
//use Psr\Log\LoggerInterface;
//use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
//
//class DashboardController extends AbstractController
//{
////    public function __construct(
////        private EntityManagerInterface $em,
////        private DashboardDataService $dashboardService,
////        private LoggerInterface $logger
////    ) {}
//
//    #[Route('/', name: 'app_home')]
//    public function home(): Response
//    {
//        return $this->redirectToRoute('app_dashboard');
//    }
//
////    #[Route('/dashboard', name: 'app_dashboard')]
////    public function index(Request $request): Response
////    {
////        try {
////
////            $merchant = $this->em->getRepository(Merchant::class)->findByCode('3928');
////
////            if (!$merchant) {
////                $this->logger->error('Merchant not found with code 3928');
////                throw new NotFoundHttpException('Merchant not found');
////            }
////
////            return $this->render('dashboard/index.html.twig',
////                $this->dashboardService->getDashboardData($merchant, $request)
////            );
////
////        } catch (\Exception $e) {
////            $this->logger->error('Dashboard error: '.$e->getMessage());
////            $this->addFlash('error', 'Unable to load dashboard data');
////
////            // Get date range
////        $dateRange = $this->getDateRange($request);
////
////            return $this->render('dashboard/error.html.twig', [
////                'error' => $e->getMessage(),
////                'dateRange' => $dateRange
////            ], new Response('', 500));
////        }
////    }
//
//
//    #[Route('/dashboard', name: 'app_dashboard')]
//    public function index(Request $request, MerchantRepository $merchantRepository, TransactionRepository $transactionRepository): Response
//    {
//        // Get merchant (assuming code 3928 for this example)
//        $merchant = $merchantRepository->findOneBy(['code' => '3928']);
//
//        if (!$merchant) {
//            throw $this->createNotFoundException('Merchant not found');
//        }
//
//        // Get date range
//        $dateRange = $this->getDateRange($request);
//        $startDate = $dateRange['startDate'];
//        $endDate = $dateRange['endDate'];
//
//        // Get transactions for the date range
//        $transactions = $transactionRepository->findByMerchantAndDateRange(
//            $merchant,
//            $startDate,
//            $endDate
//        );
//
//        // Calculate summary
//        $summary = $transactionRepository->getSummaryForMerchant(
//            $merchant,
//            $startDate,
//            $endDate
//        );
//
//        return $this->render('dashboard/index.html.twig', [
//            'merchant' => $merchant,
//            'transactions' => $transactions,
//            'summary' => $summary,
//            'dateRange' => $dateRange
//        ]);
//    }
//
////    #[Route('/dashboard', name: 'app_dashboard')]
////    public function index(Request $request): Response
////    {
////        // Hardcoded merchant data
////        $merchant = [
////            'name' => 'Smart Plan Blueprint',
////            'code' => '3928'
////        ];
////
////        // Hardcoded transactions
////        $transactions = [
////            [
////                'date' => new \DateTime('2025-07-08'),
////                'openingBalance' => 10076.10,
////                'deposits' => 0.00,
////                'sales' => 19558.00,
////                'closingBalance' => -9481.90
////            ]
////        ];
////
////        // Calculate summary
////        $summary = [
////            'opening' => 10076.10,
////            'deposits' => 0.00,
////            'sales' => 19558.00,
////            'closing' => -9481.90
////        ];
////
////        // Get date range
////        $dateRange = $this->getDateRange($request);
////
////        return $this->render('dashboard/index.html.twig', [
////            'merchant' => $merchant,
////            'transactions' => $transactions,
////            'summary' => $summary,
////            'dateRange' => $dateRange
////        ]);
////    }
//
//    private function getDateRange(Request $request): array
//    {
//        $today = new \DateTime('2025-07-08'); // Hardcoded to match your example
//        $startDate = clone $today;
//        $endDate = clone $today;
//
//        switch ($request->query->get('range')) {
//            case 'week':
//                $startDate = (clone $today)->modify('-7 days');
//                break;
//            case 'month':
//                $startDate = new \DateTime('first day of this month');
//                break;
//        }
//
//        return [
//            'startDate' => $startDate,
//            'endDate' => $endDate,
//            'display' => $this->formatDateRange($startDate, $endDate)
//        ];
//    }
//
//    private function formatDateRange(\DateTimeInterface $start, \DateTimeInterface $end): string
//    {
//        if ($start->format('Y-m-d') === $end->format('Y-m-d')) {
//            return $start->format('F jS, Y');
//        }
//
//        return $start->format('F jS') .
//            ($start->format('Y') === $end->format('Y') ? ' - ' . $end->format('F jS, Y') : ' - ' . $end->format('F jS, Y'));
//    }
//
//}

// src/Controller/DashboardController.php
namespace App\Controller;

use App\Entity\Merchant;
use App\Repository\MerchantRepository;
use App\Repository\TransactionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
//    private TransactionRepository $transactionRepository;

    public function __construct(TransactionRepository $transactionRepository)
    {
        $this->transactionRepository = $transactionRepository;
    }

    #[Route('/', name: 'app_home')]
    public function home(): Response
    {
        return $this->redirectToRoute('app_dashboard');
    }

    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(
        Request $request,
        MerchantRepository $merchantRepository,
        TransactionRepository $transactionRepository
    ): Response {
        // Get all transactions for grouping
        $allTransactions = $transactionRepository->findAllWithMerchants();

        // Group transactions by merchant ID and calculate totals
        $groupedTransactions = [];
        $grandTotals = [];
        $metricTotals = [
            'opening' => 0,
            'deposits' => 0,
            'sales' => 0,
            'commission' => 0,
            'closing' => 0
        ];

        foreach ($allTransactions as $transaction) {
            $merchant = $transaction->getMerchant();
            $merchantId = $merchant->getId();

            if (!isset($groupedTransactions[$merchantId])) {

                // Initialize grand totals
                $grandTotals = [
                    'opening' => 0,
                    'deposits' => 0,
                    'sales' => 0,
                    'closing' => 0
                ];


                $groupedTransactions[$merchantId] = [
                    'merchant' => $merchant,
                    'transactions' => [],
                    'totals' => [
                        'opening' => 0,
                        'deposits' => 0,
                        'sales' => 0,
                        'closing' => 0
                    ]
                ];
            }

            $groupedTransactions[$merchantId]['transactions'][] = $transaction;
            $groupedTransactions[$merchantId]['totals']['opening'] += $transaction->getOpeningBalance();
            $groupedTransactions[$merchantId]['totals']['deposits'] += $transaction->getDeposits();
            $groupedTransactions[$merchantId]['totals']['sales'] += $transaction->getSales();
            $groupedTransactions[$merchantId]['totals']['closing'] += $transaction->getClosingBalance();

            // Add to grand totals
            $grandTotals['opening'] += $transaction->getOpeningBalance();
            $grandTotals['deposits'] += $transaction->getDeposits();
            $grandTotals['sales'] += $transaction->getSales();
            $grandTotals['closing'] += $transaction->getClosingBalance();

            $metricTotals['opening'] += $transaction->getOpeningBalance();
            $metricTotals['deposits'] += $transaction->getDeposits();
            $metricTotals['sales'] += $transaction->getSales();
            $metricTotals['closing'] += $transaction->getClosingBalance();
        }

        // Get specific merchant data
        $merchant = $merchantRepository->findOneBy(['code' => '3928']);
        if (!$merchant) {
            throw $this->createNotFoundException('Merchant not found');
        }

        $dateRange = $this->getDateRange($request);
        $transactions = $transactionRepository->findByMerchantAndDateRange(
            $merchant,
            $dateRange['startDate'],
            $dateRange['endDate']
        );

        $summary = $transactionRepository->getSummaryForMerchant(
            $merchant,
            $dateRange['startDate'],
            $dateRange['endDate']
        );

        // Get grouped transactions (if needed)
//        $groupedTransactions = $transactionRepository->getGroupedTransactionsByDateRange(
//            $dateRange['startDate'],
//            $dateRange['endDate']
//        );

        return $this->render('dashboard/index.html.twig', [
            'merchant' => $merchant,
            'transactions' => $transactions,
            'summary' => $summary,
            'dateRange' => $dateRange,
            'groupedTransactions' => $groupedTransactions,
            'grandTotals' => $grandTotals,
            'metricTotals' => $metricTotals,
        ]);
    }

//    private function getDateRange(Request $request): array
//    {
//        $today = new \DateTime();
//        $startDate = clone $today;
//        $endDate = clone $today;
//
//        switch ($request->query->get('range')) {
//            case 'week':
//                $startDate->modify('-7 days');
//                break;
//            case 'month':
//                $startDate = new \DateTime('first day of this month');
//                break;
//        }
//
//        return [
//            'startDate' => $startDate,
//            'endDate' => $endDate,
//            'display' => $this->formatDateRange($startDate, $endDate)
//        ];
//    }

    private function getDateRange(Request $request): array
    {
        $session = $request->getSession();

        // Check if we have a stored range in session
        $storedRange = $session->get('date_range', 'today');

        // Get range from request or use stored one
        $range = $request->query->get('range', $storedRange);

        // Store the current range in session
        $session->set('date_range', $range);

        $today = new \DateTime();
        $startDate = clone $today;
        $endDate = clone $today;

        switch ($range) {
            case 'week':
                $startDate->modify('-7 days');
                break;
            case 'month':
                $startDate = new \DateTime('first day of this month');
                break;
            case 'today':
            default:
                // Default to today (no modification needed)
                break;
        }

        return [
            'startDate' => $startDate,
            'endDate' => $endDate,
            'display' => $this->formatDateRange($startDate, $endDate),
            'currentRange' => $range
        ];
    }

    private function formatDateRange(\DateTimeInterface $start, \DateTimeInterface $end): string
    {
        if ($start->format('Y-m-d') === $end->format('Y-m-d')) {
            return $start->format('F jS, Y');
        }

        return $start->format('F jS') . ' - ' . $end->format('F jS, Y');
    }
}

//
//// src/Controller/DashboardController.php
//namespace App\Controller;
//
//use App\Entity\Merchant;
//use App\Repository\MerchantRepository;
//use App\Repository\TransactionRepository;
//use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
//use Symfony\Component\HttpFoundation\Request;
//use Symfony\Component\HttpFoundation\Response;
//use Symfony\Component\Routing\Annotation\Route;
//
//class DashboardController extends AbstractController
//{
//
//    private $transactionRepository;
//
//    #[Route('/', name: 'app_home')]
//    public function home(): Response
//    {
//        return $this->redirectToRoute('app_dashboard');
//    }
//
//    #[Route('/dashboard', name: 'app_dashboard')]
//    public function index(
//        Request $request,
//        MerchantRepository $merchantRepository,
//        TransactionRepository $transactionRepository
//    ): Response {
//
//        $transactions = $this->getTransactions(); // Your method to fetch transactions
//
//        // Group transactions by merchant ID
//        $groupedTransactions = [];
//        foreach ($transactions as $transaction) {
//            $merchantId = $transaction->getMerchant()->getId();
//            $groupedTransactions[$merchantId][] = $transaction;
//        }
//
//        $merchant = $merchantRepository->findOneBy(['code' => '3928']);
//
//        if (!$merchant) {
//            throw $this->createNotFoundException('Merchant not found');
//        }
//
//        $dateRange = $this->getDateRange($request);
//        $transactions = $transactionRepository->findByMerchantAndDateRange(
//            $merchant,
//            $dateRange['startDate'],
//            $dateRange['endDate']
//        );
//
//        $summary = $transactionRepository->getSummaryForMerchant(
//            $merchant,
//            $dateRange['startDate'],
//            $dateRange['endDate']
//        );
//
//        return $this->render('dashboard/index.html.twig', [
//            'merchant' => $merchant,
//            'transactions' => $transactions,
//            'summary' => $summary,
//            'dateRange' => $dateRange
//        ]);
//    }
//
//    private function getDateRange(Request $request): array
//    {
//        $today = new \DateTime();
//        $startDate = clone $today;
//        $endDate = clone $today;
//
//        switch ($request->query->get('range')) {
//            case 'week':
//                $startDate->modify('-7 days');
//                break;
//            case 'month':
//                $startDate = new \DateTime('first day of this month');
//                break;
//        }
//
//        return [
//            'startDate' => $startDate,
//            'endDate' => $endDate,
//            'display' => $this->formatDateRange($startDate, $endDate)
//        ];
//    }
//
//    private function formatDateRange(\DateTimeInterface $start, \DateTimeInterface $end): string
//    {
//        if ($start->format('Y-m-d') === $end->format('Y-m-d')) {
//            return $start->format('F jS, Y');
//        }
//
//        return $start->format('F jS') . ' - ' . $end->format('F jS, Y');
//    }
//
//    /**
//     * Fetches transactions with related merchant data
//     *
//     * @return array
//     */
//    private function getTransactions(): array
//    {
//        // Option 1: Basic query (if you don't need filtering)
////         return $this->transactionRepository->findAll();
//
//        // Option 2: Custom query with JOIN to avoid N+1 problem
//       /* return $this->transactionRepository->createQueryBuilder('t')
//            ->addSelect('m') // Select merchant to join
//            ->leftJoin('t.merchant', 'm')
//            ->orderBy('m.name', 'ASC')
//            ->addOrderBy('t.date', 'DESC')
//            ->getQuery()
//            ->getResult();
//        */
//
//        // Option 3: If you need to filter by date range
//
//        $startDate = new \DateTime('-30 days');
//        $endDate = new \DateTime();
//
//        return $this->transactionRepository->createQueryBuilder('t')
//            ->addSelect('m')
//            ->leftJoin('t.merchant', 'm')
//            ->where('t.date BETWEEN :start AND :end')
//            ->setParameter('start', $startDate)
//            ->setParameter('end', $endDate)
//            ->orderBy('m.name', 'ASC')
//            ->addOrderBy('t.date', 'DESC')
//            ->getQuery()
//            ->getResult();
//
//    }
//
//
//}
