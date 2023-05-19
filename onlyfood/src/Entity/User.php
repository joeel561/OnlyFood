<?php

namespace App\Entity;

use App\Repository\UserRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;

#[UniqueEntity(fields: ['username'], message: 'There is already an account with this username')]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email address')]
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[Vich\Uploadable]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    public ?DateTimeImmutable $updatedAt;

    #[Groups(['account_overview', 'recipe_overview', 'weekly_plan', 'shopping_list'])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[Groups(['account_overview', 'recipe_overview', 'weekly_plan', 'shopping_list'])]
    #[Assert\NotBlank(message: 'Please enter a username')]
    #[Assert\Type(type: ['alnum'], message: "Don't use special characters in your username")]
    #[ORM\Column(type: 'string', length: 255, unique: true)]
    private ?string $username = null;

    #[Assert\NotBlank(message: 'Please enter a password')]
    #[Assert\Length(min: 8, minMessage: 'Your password must be at least {{ limit }} characters long.')]
    #[ORM\Column(type: 'string', length: 255)]
    private ?string $password = null;

    #[Groups(['account_overview'])]
    #[Assert\NotBlank]
    #[Assert\Email(message: "The email '{{ value }}' is not a valid email.")]
    #[ORM\Column(type: 'string', length: 255, unique: true)]
    private ?string $email = null;

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     */
    #[Vich\UploadableField(mapping: 'profile_picture', fileNameProperty: 'profilePictureName', size: 'imageSize')]
    private ?File $profilePictureFile = null;

    #[Groups(['account_overview', 'recipe_overview'])]
    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $profilePictureName = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $imageSize = null;

    #[Groups(['account_overview'])]
    #[ORM\Column(type: 'boolean', options: ['default' => 1])]
    private $publicMode = true;

    #[Groups(['account_overview'])]
    #[ORM\Column(type: 'boolean', options: ['default' => 1])]
    private ?bool $lightMode = true;

    #[ORM\OneToMany(mappedBy: 'userId', targetEntity: \App\Entity\Recipe::class)]
    private ?Collection $recipes = null;

    #[ORM\ManyToMany(targetEntity: Recipe::class, inversedBy: 'likedUsers')]
    private Collection $likedRecipes;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: WeeklyPlan::class)]
    private Collection $weeklyPlans;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: ShoppingList::class)]
    private ?Collection $shoppingList = null;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?int $facebookId = null;


    /**
     * 
     * @param File|UploadedFile|null $profilePictureFile
     */
    public function setProfilePictureFile(File|UploadedFile|null $profilePictureFile = null): void
    {
        $this->profilePictureFile = $profilePictureFile;

        if ($profilePictureFile instanceof File) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new DateTimeImmutable();
        }
    }

    public function getProfilePictureFile(): ?File
    {
        return $this->profilePictureFile;
    }

    public function setProfilePictureName(?string $profilePictureName): void
    {
        $this->profilePictureName = $profilePictureName;
    }

    public function getProfilePictureName(): ?string
    {
        return $this->profilePictureName;
    }

    public function setImageSize(?int $imageSize): void
    {
        $this->imageSize = $imageSize;
    }

    public function getImageSize(): ?int
    {
        return $this->imageSize;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPublicMode(): ?string
    {
        return $this->publicMode;
    }

    public function setPublicMode(?string $publicMode): self
    {
        $this->publicMode = $publicMode;

        return $this;
    }

    public function getLightMode(): ?bool
    {
        return $this->lightMode;
    }

    public function setLightMode(?bool $lightMode): self
    {
        $this->lightMode = $lightMode;

        return $this;
    }

    public function getUserIdentifier(): string
    {
        return $this->username;
    }

    public function getSalt(): null
    {
        return null;
    }

    public function getRoles(): array
    {
        return ['ROLE_USER'];
    }

    public function eraseCredentials(): void
    {
    }

    public function __serialize(): array
    {
        return [$this->id, $this->username, $this->password];
    }

    public function __unserialize(array $data): void
    {
        [$this->id, $this->username, $this->password] = $data;
    }

    public function __construct() {
        $this->recipes = new ArrayCollection();
        $this->likedRecipes = new ArrayCollection();
        $this->weeklyPlans = new ArrayCollection();
    }

    /**
     * @return Collection<Recipe>|null
     */
    public function getRecipes(): ?Collection
    {
        return $this->recipes;
    }

    public function addRecipe(Recipe $recipe): self
    {
        if (!$this->recipes->contains($recipe)) {
            $this->recipes[] = $recipe;
            $recipe->setUserId($this);
        }

        return $this;
    }

    public function removeRecipe(Recipe $recipe): self
    {
        // set the owning side to null (unless already changed)
        if (!$this->recipes->removeElement($recipe)) {
            return $this;
        }

        if ($recipe->getUserId() !== $this) {
            return $this;
        }

        $recipe->setUserId(null);
        return $this;
    }


    public function addLikedRecipe(Recipe $recipe): self
    {
        if (!$this->likedRecipes->contains($recipe)) {
            $this->likedRecipes[] = $recipe;
        }

        return $this;
    }

    public function removeLikedRecipe(Recipe $recipe): self
    {
        $this->likedRecipes->removeElement($recipe);

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
            $weeklyPlan->setUser($this);
        }

        return $this;
    }

    public function removeWeeklyPlan(WeeklyPlan $weeklyPlan): self
    {
        // set the owning side to null (unless already changed)
        if (!$this->weeklyPlans->removeElement($weeklyPlan)) {
            return $this;
        }

        if ($weeklyPlan->getUser() !== $this) {
            return $this;
        }

        $weeklyPlan->setUser(null);
        return $this;
    }

    /**
     * @return Collection<ShoppingList>|null
     */
    public function getShoppingList(): ?ShoppingList
    {
        return $this->shoppingList;
    }

    public function setShoppingList(?ShoppingList $shoppingList): self
    {
        $this->shoppingList = $shoppingList;

        return $this;
    }

    public function getFacebookId(): ?int
    {
        return $this->facebookId;
    }

    public function setFacebookId(?int $facebookId): self
    {
        $this->facebookId = $facebookId;

        return $this;
    }
}
