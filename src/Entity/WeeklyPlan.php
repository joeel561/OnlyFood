<?php

namespace App\Entity;

use App\Repository\WeeklyPlanRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=WeeklyPlanRepository::class)
 */
class WeeklyPlan
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"weekly_plan", "recipe_overview"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"weekly_plan", "recipe_overview"})
     */
    private $weekday;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"weekly_plan", "recipe_overview"})
     */
    private $meal;

    /**
     * @ORM\ManyToOne(targetEntity=Recipe::class, inversedBy="weeklyPlans")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"weekly_plan"})
     */
    private $recipe;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="weeklyPlans")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"weekly_plan"})
     */
    private $user;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"weekly_plan"})
     */
    private $weekDaySort;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"weekly_plan"})
     */
    private $mealSort;

    public function __construct()
    {
        $this->recipe = new ArrayCollection();
        $this->user = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWeekday(): ?string
    {
        return $this->weekday;
    }

    public function setWeekday(string $weekday): self
    {
        $this->weekday = $weekday;

        return $this;
    }

    public function getMeal(): ?string
    {
        return $this->meal;
    }

    public function setMeal(string $meal): self
    {
        $this->meal = $meal;

        return $this;
    }

    public function getRecipe(): ?Recipe
    {
        return $this->recipe;
    }

    public function setRecipe(?Recipe $recipe): self
    {
        $this->recipe = $recipe;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getWeekDaySort(): ?int
    {
        return $this->weekDaySort;
    }

    public function setWeekDaySort(int $weekDaySort): self
    {
        $this->weekDaySort = $weekDaySort;

        return $this;
    }

    public function getMealSort(): ?int
    {
        return $this->mealSort;
    }

    public function setMealSort(int $mealSort): self
    {
        $this->mealSort = $mealSort;

        return $this;
    }
}
