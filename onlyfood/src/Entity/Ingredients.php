<?php

namespace App\Entity;

use App\Repository\IngredientsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: IngredientsRepository::class)]
class Ingredients
{
    // not used
    // public Collection $ingredientQuantities;

    #[Groups(['recipe_overview', 'recipe_listing', 'weekly_plan'])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[Groups(['recipe_overview', 'recipe_listing', 'weekly_plan'])]
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $name = null;

    #[Groups(['recipe_overview', 'weekly_plan'])]
    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $quantity = null;

    #[Groups(['recipe_overview', 'weekly_plan'])]
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $unit = null;

    #[ORM\ManyToMany(targetEntity: Recipe::class, mappedBy: 'ingredients')]
    private Collection $recipes;

    public function __construct()
    {
        $this->recipes = new ArrayCollection();
        // not used
        // $this->ingredientQuantities = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(?int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getUnit(): ?string
    {
        return $this->unit;
    }

    public function setUnit(?string $unit): self
    {
        $this->unit = $unit;

        return $this;
    }

    /**
     * @return Collection<Recipe>
     */
    public function getRecipes(): Collection
    {
        return $this->recipes;
    }

    public function addRecipe(Recipe $recipe): self
    {
        if (!$this->recipes->contains($recipe)) {
            $this->recipes[] = $recipe;
            $recipe->addIngredient($this);
        }

        return $this;
    }

    public function removeRecipe(Recipe $recipe): self
    {
        if ($this->recipes->removeElement($recipe)) {
            $recipe->removeIngredient($this);
        }

        return $this;
    }
}
