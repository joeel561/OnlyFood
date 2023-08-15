<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Serializer\SerializerInterface;
use App\Entity\User;

class AccountOverviewController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var \Doctrine\Common\Persistence\ObjectRepository
     */

    private $tokenStorage;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(TokenStorageInterface $tokenStorage, EntityManagerInterface $entityManager)
    {
        $this->tokenStorage = $tokenStorage;
        $this->entityManager = $entityManager;
    }

    /**
     * @return Response
     * @Route("/api/account/details" , name="api_account_details" , methods={"GET"})
     */
    public function getAccountDetails(SerializerInterface $serializer): Response
    {
        $user = $this->getUser();

        $jsonContent = $serializer->serialize($user, 'json', ['groups' => 'account_overview']);

        return new Response($jsonContent, Response::HTTP_OK);
    }

    /** @param Request
     * @return Response
     * @Route("/api/account/uploadProfilePicture" , name="api_account_upload_profile_picture", methods={"POST"})
     */
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

    /** @param Request
     * @return Response
     * @Route("/api/account/changeUserInfo" , name="api_account_change_user_info")
     */
    public function getChangeUserinfo(Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager)
    {
        $user = $this->getUser();

        $content = json_decode($request->getContent(), true);

        if ($content['username'] != $user->getUserIdentifier()) {
            $checkUsername = $entityManager->getRepository(User::class)->findOneBy(['username' => $content['username']]);

            if ($checkUsername) { 
                throw new \Exception('Username already exist');
            } else {
                $user->setUsername($content['username']);
            }
        }

        $user->setPublicMode($content['publicMode']);
        $user->setLightMode($content['lightMode']);

        $this->updateDatabase($user);

        $jsonContent = $serializer->serialize($user, 'json', ['groups' => 'account_overview']);

        return new Response($jsonContent, Response::HTTP_OK);   
    }

    /** @param Request
     * @return Response
     * @Route("/api/account/deleteAccount" , name="api_account_delete_account" , methods={"DELETE"})
     */
    public function deleteAccount(Request $request)
    {
        $user = $this->getUser();
        $this->entityManager->remove($user);
        $this->entityManager->flush();

        $request->getSession()->invalidate();
        $this->tokenStorage->setToken();

        return new Response(Response::HTTP_OK);
    }

    public function updateDatabase($object)
    {
        $this->entityManager->persist($object);
        $this->entityManager->flush();
    }
}
