<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Recipe;

class ExploreRecipesController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {}

    #[Route(path: 'api/recipes/explore', name: 'app_recipes_explore', methods: ['GET'])]
    public function show(Request $request, SerializerInterface $serializer): Response
    {  

        $offset = $request->query->get('offset', 0);
        $user = $this->getUser();
        $tagIds = $request->query->get('tags', null);

        $recipes = $this->entityManager->getRepository(Recipe::class)
            ->getExploreRecipes($offset, $user, $tagIds ?: []);

        $jsonContent = $recipes ? $serializer->serialize($recipes, 'json', ['groups' => 'recipe_listing']) : null;

        return new Response($jsonContent, Response::HTTP_OK);

    }
}
