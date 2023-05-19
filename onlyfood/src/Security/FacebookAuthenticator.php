<?php

namespace App\Security;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use KnpU\OAuth2ClientBundle\Security\Authenticator\SocialAuthenticator;
use KnpU\OAuth2ClientBundle\Client\Provider\FacebookClient;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class FacebookAuthenticator extends SocialAuthenticator
{
    public function __construct(private readonly ClientRegistry $clientRegistry, private readonly EntityManagerInterface $em, private readonly RouterInterface $router)
    {
    }

    public function supports(Request $request): bool
    {
        return $request->attributes->get('_route') === 'connect_facebook_check';
    }

    public function getCredentials(Request $request)
    {
        return $this->fetchAccessToken($this->getFacebookClient());
    }

    public function getUser(\League\OAuth2\Client\Token\AccessToken $credentials, UserProviderInterface $userProvider)
    {
        /** @var FacebookUser $facebookUser */
        $facebookUser = $this->getFacebookClient()
            ->fetchUserFromToken($credentials);

        $email = $facebookUser->getEmail();

        $existingUser = $this->em->getRepository(User::class)
            ->findOneBy(['facebookId' => $facebookUser->getId()]);
        if ($existingUser instanceof \App\Entity\User) {
            return $existingUser;
        }

        $user = $this->em->getRepository(User::class)
            ->findOneBy(['email' => $email]);

        if ($user instanceof \App\Entity\User) {
            $user->setFacebookId($facebookUser->getId());
            $this->em->persist($user);
            $this->em->flush();
        }

        $uniqIdUsername = uniqid();
        $uniqIdPassword = uniqid();
        $username = $facebookUser->getFirstName() . $facebookUser->getLastName() . $uniqIdUsername;

        if (!$user instanceof \App\Entity\User) {
            $user = new User();
            $user->setEmail($email);
            $user->setUsername($username);
            $user->setPassword($uniqIdPassword);
            $user->setFacebookId($facebookUser->getId());
            $this->em->persist($user);
            $this->em->flush();
        }

        return $user;
    }

    /**
     * @return FacebookClient
     */
    private function getFacebookClient()
    {
        return $this->clientRegistry
            ->getClient('facebook_main');
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey): \Symfony\Component\HttpFoundation\RedirectResponse
    {
        $targetUrl = $this->router->generate('app_default');

        return new RedirectResponse($targetUrl);
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): \Symfony\Component\HttpFoundation\Response
    {
        $message = strtr($exception->getMessageKey(), $exception->getMessageData());

        return new Response($message, Response::HTTP_FORBIDDEN);
    }

    public function start(Request $request, AuthenticationException $authException = null): \Symfony\Component\HttpFoundation\RedirectResponse
    {
        return new RedirectResponse(
            '/login/', // might be the site, where users choose their oauth provider
            Response::HTTP_TEMPORARY_REDIRECT
        );
    }

    // ...
}