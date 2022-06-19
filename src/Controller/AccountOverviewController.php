<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Serializer\Encoder\JsonEncode;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use App\Entity\User;

class AccountOverviewController extends AbstractController
{
    protected $entityManager;

    /**
     * @var EntityManagerInterface
     */

    /**
     * @var \Doctrine\Common\Persistence\ObjectRepository
     */

    private $userRepository;

    private $tokenStorage;

    public function __construct(EntityManagerInterface $entityManager, TokenStorageInterface $tokenStorage)
    {
        $this->entityManager = $entityManager;
        $this->userRepository = $entityManager->getRepository('App:User');
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @Route("/account", name="app_account_overview")
     */
    public function index(): Response
    {
        return $this->render('account_overview/index.html.twig', [
            'controller_name' => 'AccountOverviewController',
        ]);
    }

    /**
     * @return Response
     * @Route("/account/api/details")
     */
    public function getAccountDetails(SerializerInterface $serializer): Response
    {
        $user = $this->getUser();

        $jsonContent = $serializer->serialize($user, 'json', ['groups' => 'account_overview']);

        return new Response($jsonContent, Response::HTTP_OK);
    }

    /** @param Request
     * @return Response
     * @Route("/account/api/{id}/uploadProfilePicture")
     */
    public function getUploadedProfilePicture(Request $request)
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

        return new Response(Response::HTTP_OK);
    }

    /** @param Request
     * @return Response
     * @Route("/account/api/{id}/changeUserInfo")
     */
    public function getChangeUserinfo(Request $request)
    {
        $user = $this->getUser();

        $content = json_decode($request->getContent(), true);

        if ($content['name']) {
            $user->setUsername($content['name']);
        }
        
        $user->setEmail($content['email']);
        $user->setPrivatMode($content['privatMode']);
        $user->setLightMode($content['lightMode']);

        $this->updateDatabase($user);

        return new Response(Response::HTTP_OK);                                                                     
    }

    /** @param Request
     * @return Response
     * @Route("/account/api/{id}/deleteAccount")
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
