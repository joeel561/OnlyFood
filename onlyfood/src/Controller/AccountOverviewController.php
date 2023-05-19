<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Serializer\SerializerInterface;

class AccountOverviewController extends AbstractController
{
    private UserRepository $userRepository;

    /**
     * @throws Exception
     */
    public function __construct(
        protected EntityManagerInterface $entityManager
        , private readonly TokenStorageInterface $tokenStorage
    ) {
        $userRepository = $entityManager->getRepository(User::class);

        if(!$userRepository instanceof UserRepository) {
            throw new Exception('UserRepository not found');
        }

        $this->userRepository = $userRepository;
    }

    #[Route(path: '/api/account/details', name: 'api_account_details', methods: ['GET'])]
    public function getAccountDetails(SerializerInterface $serializer): Response
    {
        $user = $this->getUser();

        $jsonContent = $serializer->serialize($user, 'json', ['groups' => 'account_overview']);

        return new Response($jsonContent, Response::HTTP_OK);
    }

    #[Route(path: '/api/account/uploadProfilePicture', name: 'api_account_upload_profile_picture', methods: ['POST'])]
    public function uploadProfilePicture(Request $request, SerializerInterface $serializer): Response
    {
        $user = $this->getUser();
        $userName = $this->getUser()->getUserIdentifier();
        $path = $request->files->get('file');
        $fileName = $userName . '.' . $path->guessExtension();
        
        if($path) {
            $user->setProfilePictureFile($path);
            $user->setProfilePictureName($fileName);
            $this->updateDatabase($user);
        }

        $jsonContent = $serializer->serialize($user, 'json', ['groups' => 'account_overview']);

        return new Response($jsonContent, Response::HTTP_OK);
    }

    #[Route(path: '/api/account/changeUserInfo', name: 'api_account_change_user_info')]
    public function getChangeUserinfo(Request $request, SerializerInterface $serializer): Response
    {
        $user = $this->getUser();

        $content = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);

        if ($content['username'] != $user->getUserIdentifier()) {
            $checkUsername = $this->userRepository->findOneBy(['username' => $content['username']]);

            if ($checkUsername) { 
                throw new Exception('Username already exist');
            }

            $user->setUsername($content['username']);
        }

        $user->setPublicMode($content['publicMode']);
        $user->setLightMode($content['lightMode']);

        $this->updateDatabase($user);

        $jsonContent = $serializer->serialize($user, 'json', ['groups' => 'account_overview']);

        return new Response($jsonContent, Response::HTTP_OK);   
    }

    #[Route(path: '/api/account/deleteAccount', name: 'api_account_delete_account', methods: ['DELETE'])]
    public function deleteAccount(Request $request): Response
    {
        $user = $this->getUser();
        $this->entityManager->remove($user);
        $this->entityManager->flush();

        $request->getSession()->invalidate();
        $this->tokenStorage->setToken();

        return new Response(Response::HTTP_OK);
    }

    public function updateDatabase(object $object): void
    {
        $this->entityManager->persist($object);
        $this->entityManager->flush();
    }
}
