<?php
// src/Controller/HeaderIcons/HelpController.php
namespace App\Controller\HeaderIcons;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HelpController extends AbstractController
{
    #[Route('/help/documentation', name: 'app_help_documentation')]
    public function documentation(): Response
    {
        return $this->render('header_icons/help/documentation.html.twig');
    }

    #[Route('/help/faq', name: 'app_help_faq')]
    public function faq(): Response
    {
        return $this->render('header_icons/help/faq.html.twig');
    }

    #[Route('/help/contact', name: 'app_help_contact')]
    public function contact(): Response
    {
        return $this->render('header_icons/help/contact.html.twig');
    }
}
