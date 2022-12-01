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
     * @ORM\ManyToMany(targetEntity=Recipe::class, inversedBy="weeklyPlans")
     * @Groups({"weekly_plan"})
     */
    private $recipe;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="weeklyPlans")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"weekly_plan"})
     */
    private $user;

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

    /**
     * @return Collection<int, Recipe>
     */
    public function getRecipe(): Collection
    {
        return $this->recipe;
    }

    public function addRecipe(Recipe $recipe): self
    {
        if (!$this->recipe->contains($recipe)) {
            $this->recipe[] = $recipe;
        }

        return $this;
    }

    public function removeRecipe(Recipe $recipe): self
    {
        $this->recipe->removeElement($recipe);

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
}
