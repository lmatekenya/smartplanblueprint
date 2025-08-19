<?php
// src/Controller/LimitsAndDocs/DocumentUploadController.php
namespace App\Controller\LimitsAndDocs;

use App\Entity\LimitsAndDocs\AgreementDocument;
use App\Entity\Merchant;
use App\Repository\LimitsAndDocs\AgreementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/merchant')]
class DocumentUploadController extends AbstractController
{
    #[Route('/{id}/upload-document', name: 'upload_merchant_document', methods: ['POST'])]
    public function uploadDocument(
        Merchant $merchant,
        Request $request,
        EntityManagerInterface $em,
        SluggerInterface $slugger
    ): Response {
        $file = $request->files->get('file');
        $documentType = $request->request->get('type');

        if (!$file instanceof UploadedFile) {
            return $this->json(['error' => 'No file uploaded'], 400);
        }

        // Validate file type and size
        $validMimeTypes = ['application/pdf', 'image/jpeg', 'image/png'];
        if (!in_array($file->getMimeType(), $validMimeTypes)) {
            return $this->json(['error' => 'Invalid file type. Only PDF, JPEG, and PNG are allowed.'], 400);
        }

        if ($file->getSize() > 5 * 1024 * 1024) { // 5MB max
            return $this->json(['error' => 'File too large. Maximum size is 5MB.'], 400);
        }

        // Generate safe filename
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $slugger->slug($originalFilename);
        $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

        // Move file to upload directory
        try {
            $file->move(
                $this->getParameter('documents_directory'),
                $newFilename
            );
        } catch (FileException $e) {
            return $this->json(['error' => 'File upload failed'], 500);
        }

        // Create and persist document entity
        $document = new AgreementDocument();
        $document->setMerchant($merchant)
            ->setType($documentType)
            ->setFilename($newFilename)
            ->setOriginalFilename($file->getClientOriginalName())
            ->setMimeType($file->getMimeType())
            ->setSize($file->getSize())
            ->setUploadedAt(new \DateTimeImmutable());

        $em->persist($document);
        $em->flush();

        return $this->json([
            'success' => true,
            'filename' => $newFilename,
            'originalFilename' => $file->getClientOriginalName()
        ]);
    }

    #[Route('/{id}/document-counts', name: 'merchant_document_counts', methods: ['GET'])]
    public function getDocumentCounts(Merchant $merchant, AgreementRepository $agreementRepo): Response
    {
        return $this->json([
            'view' => $agreementRepo->count(['merchant' => $merchant, 'type' => 'view']),
            'registration' => $agreementRepo->count(['merchant' => $merchant, 'type' => 'registration']),
            'affidavit' => $agreementRepo->count(['merchant' => $merchant, 'type' => 'affidavit'])
        ]);
    }

//    #[Route('/{id}/document-info', name: 'merchant_document_info', methods: ['GET'])]
//    public function getDocumentInfo(Merchant $merchant, Request $request, AgreementRepository $agreementRepo): Response
//    {
//        $type = $request->query->get('type');
//        $document = $agreementRepo->findOneBy([
//            'merchant' => $merchant,
//            'type' => $type
//        ], ['uploadedAt' => 'DESC']);
//
//        if (!$document) {
//            return $this->json(['error' => 'Document not found'], 404);
//        }
//
//        return $this->json([
//            'url' => $this->generateUrl('view_merchant_document', [
//                'id' => $merchant->getId(),
//                'type' => $type,
//                'filename' => $document->getFilename()
//            ]),
//            'filename' => $document->getOriginalFilename(),
//            'uploadedAt' => $document->getUploadedAt()->format('c'),
//            'type' => $document->getType()
//        ]);
//    }

    #[Route('/{id}/document-info', name: 'merchant_document_info', methods: ['GET'])]
    public function getDocumentInfo(Merchant $merchant, Request $request, AgreementRepository $agreementRepo, UrlGeneratorInterface $urlGenerator): Response
    {
        $type = $request->query->get('type');
        $document = $agreementRepo->findOneBy([
            'merchant' => $merchant,
            'type' => $type
        ], ['uploadedAt' => 'DESC']);

        if (!$document) {
            return $this->json(['error' => 'Document not found'], 404);
        }

        return $this->json([
            'url' => $urlGenerator->generate('view_merchant_document', [
                'id' => $merchant->getId(),
                'type' => $type,
                'filename' => $document->getFilename()
            ]),
            'filename' => $document->getOriginalFilename(),
            'uploadedAt' => $document->getUploadedAt()->format('c'),
            'type' => $document->getType()
        ]);
    }

    #[Route('/{id}/documents/{type}/{filename}', name: 'view_merchant_document', methods: ['GET'])]
    public function viewDocument(Merchant $merchant, string $type, string $filename): Response
    {
        $filePath = $this->getParameter('documents_directory').'/'.$filename;

        if (!file_exists($filePath)) {
            throw $this->createNotFoundException('Document file not found');
        }

        $response = new Response(file_get_contents($filePath));

        // Set appropriate headers based on file type
        $mimeType = mime_content_type($filePath);
        $response->headers->set('Content-Type', $mimeType);

        // For PDFs, display in browser
        if ($mimeType === 'application/pdf') {
            $response->headers->set('Content-Disposition', 'inline; filename="'.$filename.'"');
        } else {
            // For images, also display in browser
            $response->headers->set('Content-Disposition', 'inline');
        }

        return $response;
    }
}
