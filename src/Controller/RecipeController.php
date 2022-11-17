<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Recipe;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Ingredients;
use App\Entity\Tag;
use App\Entity\IngredientQuantity;
use Symfony\Component\Serializer\SerializerInterface;

class RecipeController extends AbstractController
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
     * @Route("/api/createRecipe", name="app_recipe_create")
     */
    public function createRecipe(Request $request, SerializerInterface $serializer)
    {
        $user = $this->getUser();
        $content = json_decode($request->get('recipe'), true);
        $userName = $this->getUser()->getUserIdentifier();
        $path = $request->files->get('file');
       
        if ($content['id']) {
            $recipe = $this->entityManager->getRepository(Recipe::class)->find($content['id']);
            $recipe->setPortion($content['portion']);
            $recipe->setPrepTime($content['prepTime']);

            if($path) {
                $fileName = $userName . '.' . $path->guessExtension();

                $recipe->setImageFile($path);
                $recipe->setImageName($fileName);
            }
        } else {
            $recipe = new Recipe();
            $recipe->setUserId($user);
        }

        if (!$content['name']) { 
            throw new \Exception('Recipe name is required');
        } else {
            $recipe->setName($content['name']);
        }

        if (!$content['method']) { 
            throw new \Exception('Method is required');
        } else {
            $recipe->setMethod($content['method']);
        }

        if (!$content['difficulty']) { 
            throw new \Exception('Difficulty is required');
        } else {
            $recipe->setDifficulty($content['difficulty']);
        }

        $this->updateDatabase($recipe);

        if (!$content['tags']) {
            throw new \Exception('Tags are required');
        } else {
           foreach ($content['tags'] as $tag) {
                $tagRepo = $this->entityManager->getRepository(Tag::class)->findOneBy(['name' => $tag]);
                if (!$tagRepo) {
                    $tagRepo = new Tag();
                    $tagRepo->setName($tag['name']);
                    $tagRepo->addRecipe($recipe);
                } else {
                    $recipe->addTag($tagRepo);
                }
                $this->updateDatabase($tagRepo);
            }
        }

        if (!$content['ingredients']) { 
            throw new \Exception('Ingredients are required');
        } else {
            foreach ($content['ingredients'] as $ingredient) {
                $ingredientRepo = $this->entityManager->getRepository(Ingredients::class);
                $ingredientEntity = $ingredientRepo->findOneBy(['id' => $ingredient['id']]);
                if (!$ingredientEntity) {
                    $ingredientEntity = new Ingredients();
                    $this->setIngredients($ingredientEntity, $ingredient);
                    $ingredientEntity->addRecipe($recipe);
                } else {
                    $this->setIngredients($ingredientEntity, $ingredient);
                }

                $this->updateDatabase($ingredientEntity);
            }
        }

        $jsonContent = $serializer->serialize($recipe, 'json', ['groups' => 'recipe_overview']);

        return new Response($jsonContent, Response::HTTP_OK);
    }

    /**
     * @Route("/api/recipe/{id}", name="app_recipe_show", methods={"GET"})
     */
    public function show(Request $request, SerializerInterface $serializer)
    {
        $recipe = $this->entityManager->getRepository(Recipe::class)->findOneBy(['id' => $request->get('id')]);
        $user = $this->getUser();
        $isUserRecipe = false;

        if($recipe){
            $recipeUser = $recipe->getUserId();

            if ($recipeUser == $user) {
                $isUserRecipe = true;
            }
        }
        
        $jsonContent = $serializer->serialize($recipe, 'json', ['groups' => 'recipe_overview']);

        $newResponse = array(
            'recipe' => $jsonContent,
            'isUserRecipe' => $isUserRecipe
        );

        return new JsonResponse($newResponse, Response::HTTP_OK);
    }
    
    /**
     * @Route("/api/editRecipe/{id}", name="app_recipe_edit")
     */
    public function editRecipe(Request $request, SerializerInterface $serializer, $id)
    {
        $user = $this->getUser();
        $recipe = $this->entityManager->getRepository(Recipe::class)->findOneBy(['id' => $id]);
        $jsonContent = $serializer->serialize($recipe, 'json', ['groups' => 'recipe_overview']);

        return new Response($jsonContent, Response::HTTP_OK);
    }


    /** @param Request
     * @return Response
     * @Route("/api/recipe/{id}/cancelRecipe" , name="api_delete_recipe", methods={"DELETE"})
     */
    public function cancelRecipe(Request $request, SerializerInterface $serializer): Response
    {
        $user = $this->getUser();
        $recipe = $this->entityManager->getRepository(Recipe::class);
        $recipeUser = $recipe->findAll(['userId' => $user->getId()]);
        $recipeId = $recipe->findOneBy(['id' => $request->get('id')]);
        $ingredients = $this->entityManager->getRepository(Ingredients::class)->getRecipeId($recipeId);

        if ($recipeUser) {
            if ($ingredients) {
                foreach ($ingredients as $ingredient) {
                    $this->entityManager->remove($ingredient);
                }
            }

            $this->entityManager->remove($recipeId);
            $this->entityManager->flush();
        }

        $jsonContent = $serializer->serialize($recipeId, 'json', ['groups' => 'recipe_overview']);

        return new Response($jsonContent, Response::HTTP_OK);
    }


    private function setIngredients($ingredientEntity, $ingredient) 
    {
        if ($ingredient['name']) { 
            $ingredientEntity->setName($ingredient['name']);
        }  else {
            throw new \Exception('Ingredient Name is required');
        }
        if ($ingredient['quantity']) { 
            if(is_numeric($ingredient['quantity'])) {
                $ingredientEntity->setQuantity($ingredient['quantity']);
            } else {
                throw new \Exception('Quantity has to be an integer');
            }
            $ingredientEntity->setQuantity($ingredient['quantity']);
        } else {
            throw new \Exception('Quantity is required');
        }

        $ingredientEntity->setUnit($ingredient['unit']);
    }

    public function updateDatabase($object)
    {
        $this->entityManager->persist($object);
        $this->entityManager->flush();
    }
}
