<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Recipe;

class RecipeOverviewController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("api/recipes/overview", name="app_recipe_overview", methods={"GET"})
     */
    public function show(SerializerInterface $serializer)
    {
        $user = $this->getUser();
        $recipes = $this->entityManager->getRepository(Recipe::class)->findBy(['userId' => $user->getId()]);
        
        $jsonContent = $serializer->serialize($recipes, 'json', ['groups' => 'recipe_listing']);

        return new Response($jsonContent, Response::HTTP_OK);	
    }
}
