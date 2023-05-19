<?php

namespace App\Controller;

use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FacebookController extends AbstractController
{
    #[Route(path: '/connect/facebook', name: 'connect_facebook_start')]
    public function connectAction(ClientRegistry $clientRegistry): Response
    {
        return $clientRegistry
            ->getClient('facebook_main')
            ->redirect(['public_profile', 'email']);
    }

    #[Route(path: '/connect/facebook/check', name: 'connect_facebook_check')]
    public function connectCheckAction(ClientRegistry $clientRegistry): void
    {
        
    }
}
