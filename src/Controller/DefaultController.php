<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/dashboard", name="app_default" , methods={"GET"})
     */
    public function index(): Response
    {
        return $this->render('base.html.twig');
    }

    /**
     * @Route("/", name="app_landingpage" , methods={"GET"})
     */
    public function landingpage(): Response
    {
        return $this->render('landingpage/landingpage.html.twig');
    }
}
