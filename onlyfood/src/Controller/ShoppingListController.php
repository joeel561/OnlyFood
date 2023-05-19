<?php

namespace App\Controller;

use Exception;
use JsonException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\ShoppingList;
use App\Entity\Ingredients;
use Symfony\Component\HttpFoundation\Request;

class ShoppingListController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {}

    #[Route(path: 'api/shopping/list', name: 'app_shopping_list')]
    public function show(SerializerInterface $serializer): Response
    {
        $user = $this->getUser();
        $shoppingList = $this->entityManager->getRepository(ShoppingList::class)->findBy(['user' => $user->getId()]);

        $jsonContent = $serializer->serialize($shoppingList, 'json', ['groups' => 'shopping_list']);

        return new Response($jsonContent, Response::HTTP_OK);
    }

    /**
     * @throws JsonException
     * @throws Exception
     */
    #[Route(path: '/api/shopping-list/upsert/', name: 'app_shopping_upsert_list')]
    public function upsertList(Request $request, SerializerInterface $serializer): Response
    {
        $user = $this->getUser();
        $content = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $shoppingList = $this->entityManager->getRepository(ShoppingList::class)->findOneBy(['user' => $user->getId()]);

        if ($shoppingList instanceof ShoppingList) {
            if ($content['ingredients']) {
                $shoppingList->setIngredients($content['ingredients']);
            } else {
                throw new Exception('No ingredients found');
            }
        } else {
            $shoppingList = new ShoppingList();
            $shoppingList->setUser($user);

            if ($content['ingredients']) {
                $shoppingList->setIngredients($content['ingredients']);
            } else {
                throw new Exception('No ingredients found');
            }
        }

        $this->updateDatabase($shoppingList);

        $jsonContent = $serializer->serialize($shoppingList, 'json', ['groups' => 'shopping_list']);

        return new Response($jsonContent, Response::HTTP_OK);

    }

    #[Route(path: '/api/shopping-list/delete/', name: 'app_shopping_delete_list')]
    public function deleteList(SerializerInterface $serializer): Response
    {
        $user = $this->getUser();
        $shoppingList = $this->entityManager->getRepository(ShoppingList::class)->findOneBy(['user' => $user->getId()]);

        if ($shoppingList instanceof ShoppingList) {
            $this->entityManager->remove($shoppingList);
            $this->entityManager->flush();
        }

        $jsonContent = $serializer->serialize($shoppingList, 'json', ['groups' => 'shopping_list']);

        return new Response($jsonContent, Response::HTTP_OK);

    }

    public function updateDatabase(object $object): void
    {
        $this->entityManager->persist($object);
        $this->entityManager->flush();
    }
}
