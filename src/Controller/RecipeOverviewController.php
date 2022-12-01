<?php

namespace App\Controller;

use App\Entity\Ingredients;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Recipe;
use App\Entity\Tag;

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
    public function show(Request $request, SerializerInterface $serializer)
    {  
        // Variablen aus dem Request holen
        $offset = $request->query->get('offset', 0);
        $user = $this->getUser();
        $tagIds = $request->query->get('tags', []);
        $ingredientIds = $request->query->get('ingredients', []);

        $recipes = $this->entityManager->getRepository(Recipe::class)
            ->getRecipes($offset, $user, $tagIds, $ingredientIds);

        if ($recipes) {
            $jsonContent = $serializer->serialize($recipes, 'json', ['groups' => 'recipe_listing']);
        } else {
            $jsonContent = [];
        }

        return new Response($jsonContent, Response::HTTP_OK);
        

/*         if ($ingredients) {
            $ingredientsRepo = $this->entityManager->getRepository(Ingredients::class);
            $filterdIngredients = $ingredientsRepo->getNotInIngredientsList($ingredients);

            if ($filterdIngredients) {
                foreach ($filterdIngredients as $filterIngredient) {
                   
                  //  $new =  $ingredientsRepo->getFilteredRecipes($filterIngredient);
                  
                }
            }
        } */

    }

    /**
     * @Route("api/recipes/showTags", name="app_tags", methods={"GET"})
     */
    public function showTags(SerializerInterface $serializer)
    {
        $tags = $this->entityManager->getRepository(Tag::class)->findAll();
        
        $jsonContent = $serializer->serialize($tags, 'json', ['groups' => 'recipe_listing']);

        return new Response($jsonContent, Response::HTTP_OK);	
    }

    /**
     * @Route("api/recipes/showIngredients", name="app_ingredients", methods={"GET"})
     */
    public function showIngredients(SerializerInterface $serializer)
    {
        $ingredients = $this->entityManager->getRepository(Ingredients::class)->getFilterIngredients();

        if (!$ingredients) {
            return new Response('No ingredients found', Response::HTTP_NOT_FOUND);
        }
        
        $jsonContent = $serializer->serialize($ingredients, 'json', ['groups' => 'recipe_listing']);

        return new Response($jsonContent, Response::HTTP_OK);	
    }

    /**
     * @Route("api/recipes/searchResult", name="app_recipes_search_result", methods={"GET"})
     */
    public function getSearchResult(Request $request, SerializerInterface $serializer) 
    {
        $search = $request->query->get('search');

        foreach ($search as $searchItem ){
            $recipes = $this->entityManager->getRepository(Recipe::class)->getSearchResult($searchItem);
        }


        if (!$recipes) {
            return new Response('No recipes found', Response::HTTP_NOT_FOUND);
        }

        $jsonContent = $serializer->serialize($recipes, 'json', ['groups' => 'recipe_listing']);

        return new Response($jsonContent, Response::HTTP_OK);
    }
}
