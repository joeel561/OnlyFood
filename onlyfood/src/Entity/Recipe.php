<?php

namespace App\Entity;

use App\Repository\RecipeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Serializer\Annotation\Groups;

#[Vich\Uploadable]
#[ORM\Entity(repositoryClass: RecipeRepository::class)]
class Recipe
{
    #[Groups(['recipe_overview', 'recipe_listing', 'weekly_plan', 'add_weekly_plan'])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[Groups(['recipe_overview', 'recipe_listing', 'weekly_plan', 'add_weekly_plan'])]
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $name = null;

    #[Groups(['recipe_overview', 'recipe_listing', 'weekly_plan'])]
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $prepTime = null;

    #[Groups(['recipe_overview', 'weekly_plan'])]
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $difficulty = null;

    #[Groups(['recipe_overview'])]
    #[ORM\Column(type: 'integer', nullable: true, options: ['default' => 1])]
    private ?int $portion = 1;

    #[Groups(['recipe_overview'])]
    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $method = null;

    #[Groups(['recipe_overview'])]
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'recipes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $userId = null;

    #[Groups(['recipe_overview'])]
    #[Vich\UploadableField(mapping: 'recipe_pictures', fileNameProperty: 'imageName', size: 'imageSize')]
    private ?File $imageFile = null;

    #[Groups(['recipe_overview', 'recipe_listing', 'weekly_plan'])]
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $imageName = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $imageSize = null;

    #[Groups(['recipe_overview', 'recipe_listing', 'weekly_plan'])]
    #[ORM\ManyToMany(targetEntity: Ingredients::class, inversedBy: 'recipes')]
    private Collection $ingredients;

    #[Groups(['recipe_overview', 'recipe_listing', 'weekly_plan'])]
    #[ORM\ManyToMany(targetEntity: Tag::class, mappedBy: 'recipes')]
    private Collection $tags;

    #[Groups(['recipe_overview', 'recipe_listing'])]
    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'likedRecipes')]
    private Collection $likedUsers;

    #[ORM\OneToMany(mappedBy: 'recipe', targetEntity: WeeklyPlan::class)]
    private Collection $weeklyPlans;

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
     * @param File|UploadedFile|null $imageFile
     */
    public function setImageFile(File|UploadedFile|null $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        // if ($imageFile instanceof File) {
        //    is currently empty
        // }
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
     * @return Collection<Ingredients>
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
     * @return Collection<Tag>
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
     * @return Collection<User>
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
     * @return Collection<WeeklyPlan>
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
        if (!$this->weeklyPlans->removeElement($weeklyPlan)) {
            return $this;
        }

        if ($weeklyPlan->getRecipe() !== $this) {
            return $this;
        }

        $weeklyPlan->setRecipe(null);
        return $this;
    }
}
