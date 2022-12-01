<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\WeeklyPlan;
use App\Entity\Recipe;
use Symfony\Component\HttpFoundation\Request;

class WeeklyPlanController extends AbstractController
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
     * @Route("/weekly-plan", name="app_weekly_plan")
     */
    public function show(Request $request, SerializerInterface $serializer)
    {
        $user = $this->getUser();
        //$weeklyPlan = $this->entityManager->getRepository(WeeklyPlan::class)->findBy(['user_id' => $user->getId()]);
       
        //$jsonContent = $serializer->serialize($weeklyPlan, 'json', ['groups' => 'weekly_plan']);

       return new Response( Response::HTTP_OK);
    }
    
    /**
     * @Route("/api/weekly-plan/create/", name="app_create_weekly_plan")
     */
    public function createWeeklyPlan(Request $request, SerializerInterface $serializer)
    {
        $user = $this->getUser();
        $content = json_decode($request->getContent(), true);
        $recipe = $this->entityManager->getRepository(Recipe::class)->find($content['recipeId']);

        if ($content) {
            $weeklyPlan = new WeeklyPlan();
            $weeklyPlan->setUser($user);
            $weeklyPlan->addRecipe($recipe);

            if (!$content['day']) {
                throw new \Exception('Day is required');
            } else {
                $weeklyPlan->setWeekday($content['day']);
            }
            if (!$content['meal']) {
                throw new \Exception('Meal is required');
            } else {
                $weeklyPlan->setMeal($content['meal']);
            }

            $this->updateDatabase($weeklyPlan);
        }

        $jsonContent = $serializer->serialize($weeklyPlan, 'json', ['groups' => 'weekly_plan']);
        
        return new Response($jsonContent, Response::HTTP_OK);
    }

    /**
     * @Route("/api/weekly-plan/remove/", name="app_remove_weekly_plan")
     */
    public function removeWeeklyPlan(Request $request, SerializerInterface $serializer)
    {
        $user = $this->getUser();
        $content = json_decode($request->getContent(), true);
        $recipe = $this->entityManager->getRepository(Recipe::class)->find($content['recipeId']);

        if ($content) {
            $weeklyPlan = $this->entityManager->getRepository(WeeklyPlan::class)->findOneBy(['user' => $user, 'recipe' => $recipe, 'weekday' => $content['day'], 'meal' => $content['meal']]);
            $this->entityManager->remove($weeklyPlan);
            $this->entityManager->flush();
        }

        $jsonContent = $serializer->serialize($weeklyPlan, 'json', ['groups' => 'weekly_plan']);
        
        return new Response($jsonContent, Response::HTTP_OK);
    }

    public function updateDatabase($object)
    {
        $this->entityManager->persist($object);
        $this->entityManager->flush();
    }
}
