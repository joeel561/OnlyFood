<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\WeeklyPlan;
use App\Entity\Recipe;
use Symfony\Component\Form\Extension\Core\Type\WeekType;
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
     * @Route("/api/weekly-plan/", name="app_weekly_plan")
     */
    public function show(Request $request, SerializerInterface $serializer)
    {
        $user = $this->getUser();
        $weeklyPlan = $this->entityManager->getRepository(WeeklyPlan::class)->findBy(['user' => $user->getId()]);
        $jsonContent = $serializer->serialize($weeklyPlan, 'json', ['groups' => 'weekly_plan']);
        return new Response($jsonContent, Response::HTTP_OK);
    }

    /**
     * @Route("/api/weekly-plan/update/", name="app_update_weekly_plan")
     */
    public function createWeeklyPlan(Request $request, SerializerInterface $serializer)
    {
        $user = $this->getUser();
        $content = json_decode($request->getContent(), true);
        $recipe = $this->entityManager->getRepository(Recipe::class)->find($content['recipeId']);

        if ($content['id']) {
            $weeklyPlan = $this->entityManager->getRepository(WeeklyPlan::class)->findOneBy(['id' => $content['id'], 'user' => $user->getId()]);

            $this->setWeeklyPlanContent($weeklyPlan, $content, $recipe);

            $this->updateDatabase($weeklyPlan);
        } else {
            $weeklyPlan = new WeeklyPlan();
            $weeklyPlan->setUser($user);

            $this->setWeeklyPlanContent($weeklyPlan, $content, $recipe);

            $this->updateDatabase($weeklyPlan);
        }

        $jsonContent = $serializer->serialize($weeklyPlan, 'json', ['groups' => 'weekly_plan']);

        return new Response($jsonContent, Response::HTTP_OK);
    }

    private function setWeeklyPlanContent($weeklyPlan, $content, $recipe)
    {

        if (!$content['day']) {
            return new Response('Day is not set', Response::HTTP_BAD_REQUEST);
        }

        if (!$content['meal']) {
            return new Response('Meal is not set', Response::HTTP_BAD_REQUEST);
        }

        $inWeekDay = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        $inMeal = ['breakfast', 'lunch', 'dinner', 'snack'];

        if (in_array($content['day']['weekday'], $inWeekDay)) {
            $weeklyPlan->setWeekday($content['day']['weekday']);
            $weeklyPlan->setWeekDaySort($content['day']['weekDaySort']);
        }

        if (in_array($content['meal']['meal'], $inMeal)) {
            $weeklyPlan->setMeal($content['meal']['meal']);
            $weeklyPlan->setMealSort($content['meal']['mealSort']); 
        }

        $weeklyPlan->setRecipe($recipe);
    }

    /**
     * @Route("/api/weekly-plan/remove/", name="app_remove_weekly_plan")
     */
    public function removeWeeklyPlan(Request $request, SerializerInterface $serializer)
    {
        $user = $this->getUser();
        $content = json_decode($request->getContent(), true);

        if ($content) {
            $weeklyPlan = $this->entityManager->getRepository(WeeklyPlan::class)->findOneBy(['id' => $content['id'], 'user' => $user->getId()]);

            $this->entityManager->remove($weeklyPlan);
            $this->entityManager->flush();
        }
        
        $weeklyPlans = $this->entityManager->getRepository(WeeklyPlan::class)->findBy(['user' => $user]);

        $jsonContent = $serializer->serialize($weeklyPlans, 'json', ['groups' => 'weekly_plan']);

        return new Response($jsonContent, Response::HTTP_OK);
    }

    public function updateDatabase($object)
    {
        $this->entityManager->persist($object);
        $this->entityManager->flush();
    }

    /**
     * @Route("/api/weekly-plan/showRecipes", name="app_weekly_plan_show_recipe")
     */
    public function showRecipesToWeeklyPlan(Request $request, SerializerInterface $serializer)
    {
        $user = $this->getUser();
        $content = json_decode($request->getContent(), true);
        $recipes = $this->entityManager->getRepository(Recipe::class)->getRecipesforWeeklyPlan($user);

        $jsonContent = $serializer->serialize($recipes, 'json', ['groups' => 'add_weekly_plan']);

        return new Response($jsonContent, Response::HTTP_OK);
    }

    /** 
     * @Route("/api/weekly-plan/todaysmealplan", name="app_weekly_plan_todays_meal_prep")
    */
    public function todaysMealPlan(Request $request, SerializerInterface $serializer)
    {
        $user = $this->getUser();
        $content = json_decode($request->getContent(), true);
        if ($content['date']) {
            $todaysMealPlan = $this->entityManager->getRepository(WeeklyPlan::class)->getTodaysMealPlan($user, $content['date']);

            $jsonContent = $serializer->serialize($todaysMealPlan, 'json', ['groups' => 'weekly_plan']);
        } else {
            $jsonContent = null;
        }

        return new Response($jsonContent, Response::HTTP_OK);
    }
    
}
