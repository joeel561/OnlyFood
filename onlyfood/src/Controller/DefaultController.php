<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route(path: '/dashboard', name: 'app_default', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('base.html.twig');
    }

    #[Route(path: '/', name: 'app_landingpage', methods: ['GET'])]
    public function landingpage(): Response
    {
        return $this->render('landingpage/landingpage.html.twig');
    }
}
