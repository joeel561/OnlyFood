<?php

namespace App\Entity;

use App\Repository\RecipeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=RecipeRepository::class)
 * @Vich\Uploadable
 */
class Recipe
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"recipe_overview", "recipe_listing", "weekly_plan", "add_weekly_plan"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"recipe_overview", "recipe_listing", "weekly_plan", "add_weekly_plan"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"recipe_overview", "recipe_listing", "weekly_plan"})
     */
    private $prepTime;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"recipe_overview", "weekly_plan"})
     */
    private $difficulty;

    /**
     * @ORM\Column(type="integer", nullable=true, options={"default":"1"})
     * @Groups({"recipe_overview"})
     */
    private $portion = '1';

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"recipe_overview"})
     */
    private $method;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="recipes")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"recipe_overview"})
     */
    private $userId;

    /**
     * @var File|null
     *  @Vich\UploadableField(mapping="recipe_pictures", fileNameProperty="imageName", size="imageSize")
     * @Groups({"recipe_overview"})
     */
    private $imageFile;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @var int|null
     * @Groups({"recipe_overview", "recipe_listing", "weekly_plan"})
     */
    private $imageName;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @var int|null
     */
    private $imageSize;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @var \DateTimeInterface|null
     */
    private $updatedAt;

    /**
     * @ORM\ManyToMany(targetEntity=Ingredients::class, inversedBy="recipes")
     * @Groups({"recipe_overview", "recipe_listing", "weekly_plan"})
     */
    private $ingredients;

    /**
     * @ORM\ManyToMany(targetEntity=Tag::class, mappedBy="recipes")
     * @Groups({"recipe_overview", "recipe_listing", "weekly_plan"})
     */
    private $tags;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, mappedBy="likedRecipes")
     * @Groups({"recipe_overview", "recipe_listing"})
     */
    private $likedUsers;

    /**
     * @ORM\OneToMany(targetEntity=WeeklyPlan::class, mappedBy="recipe")
     * 
     */
    private $weeklyPlans;

    public function __construct()
    {
        $this->ingredients = new ArrayCollection();
        $this->tags = new ArrayCollection();
        $this->likedUsers = new ArrayCollection();
        $this->weeklyPlans = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPrepTime(): ?string
    {
        return $this->prepTime;
    }

    public function setPrepTime(?string $prepTime): self
    {
        $this->prepTime = $prepTime;

        return $this;
    }

    public function getDifficulty(): ?string
    {
        return $this->difficulty;
    }

    public function setDifficulty(string $difficulty): self
    {
        $this->difficulty = $difficulty;

        return $this;
    }

    public function getPortion(): ?int
    {
        return $this->portion;
    }

    public function setPortion(int $portion): self
    {
        $this->portion = $portion;

        return $this;
    }

    public function getMethod(): ?string
    {
        return $this->method;
    }

    public function setMethod(string $method): self
    {
        $this->method = $method;

        return $this;
    }

    public function getUserId(): ?User
    {
        return $this->userId;
    }

    public function setUserId(?User $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    /**
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $imageFile
     */
    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if (null !==$imageFile) {
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function setImageName(?string $imageName): self
    {
        $this->imageName = $imageName;

        return $this;
    }

    public function getImageSize(): ?int
    {
        return $this->imageSize;
    }

    public function setImageSize(?int $imageSize): self
    {
        $this->imageSize = $imageSize;

        return $this;
    }

    /**
     * @return Collection<int, Ingredients>
     */
    public function getIngredients(): Collection
    {
        return $this->ingredients;
    }

    public function addIngredient(Ingredients $ingredient): self
    {
        if (!$this->ingredients->contains($ingredient)) {
            $this->ingredients[] = $ingredient;
        }

        return $this;
    }

    public function removeIngredient(Ingredients $ingredient): self
    {
        $this->ingredients->removeElement($ingredient);

        return $this;
    }

    /**
     * @return Collection<int, Tag>
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
            $tag->addRecipe($this);
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        if ($this->tags->removeElement($tag)) {
            $tag->removeRecipe($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getLikedUsers(): Collection
    {
        return $this->likedUsers;
    }

    public function addLikedUser(User $likedUser): self
    {
        if (!$this->likedUsers->contains($likedUser)) {
            $this->likedUsers[] = $likedUser;
            $likedUser->addLikedRecipe($this);
        }

        return $this;
    }

    public function removeLikedUser(User $likedUser): self
    {
        if ($this->likedUsers->removeElement($likedUser)) {
            $likedUser->removeLikedRecipe($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, WeeklyPlan>
     */
    public function getWeeklyPlans(): Collection
    {
        return $this->weeklyPlans;
    }

    public function addWeeklyPlan(WeeklyPlan $weeklyPlan): self
    {
        if (!$this->weeklyPlans->contains($weeklyPlan)) {
            $this->weeklyPlans[] = $weeklyPlan;
            $weeklyPlan->setRecipe($this);
        }

        return $this;
    }

    public function removeWeeklyPlan(WeeklyPlan $weeklyPlan): self
    {
        if ($this->weeklyPlans->removeElement($weeklyPlan)) {
            if ($weeklyPlan->getRecipe() === $this) {
                $weeklyPlan->setRecipe(null);
            }
        }

        return $this;
    }
}
