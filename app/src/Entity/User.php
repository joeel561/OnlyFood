<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @Vich\Uploadable
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"username"}, message="There is already an account with this username")
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email address")
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"account_overview", "recipe_overview", "weekly_plan", "shopping_list"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Groups({"account_overview" , "recipe_overview", "weekly_plan", "shopping_list"})
     * @Assert\NotBlank(message="Please enter a username")
     * @Assert\Type(type={"alnum"} , message="Don't use special characters in your username")
     * 
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Please enter a password")
     * @Assert\Length(min=8, minMessage="Your password must be at least {{ limit }} characters long.")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Groups({"account_overview"})
     * @Assert\NotBlank
     * @Assert\Email(message = "The email '{{ value }}' is not a valid email.")
     */
    private $email;

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     * @Vich\UploadableField(mapping="profile_picture", fileNameProperty="profilePictureName", size="imageSize")
     * 
     * @var File|null
     */
    private $profilePictureFile;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Groups({"account_overview" , "recipe_overview"})
     */
    private $profilePictureName;

    /**
     * @ORM\Column(type="integer", nullable=true)
     *
     * @var int|null
     */
    private $imageSize;

    /**
     * @ORM\Column(type="boolean", options={"default":"1"})
     * @Groups({"account_overview"})
     */
    private $publicMode = true;

    /**
     * @ORM\Column(type="boolean", options={"default":"1"})
     * @Groups({"account_overview"})
     */
    private $lightMode = true;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Recipe", mappedBy="userId", orphanRemoval=true)
     * 
     */
    private $recipes;

    /**
     * @ORM\ManyToMany(targetEntity=Recipe::class, inversedBy="likedUsers", cascade={"persist"})
     */
    private $likedRecipes;

    /**
     * @ORM\OneToMany(targetEntity=WeeklyPlan::class, mappedBy="user", orphanRemoval=true)
     */
    private $weeklyPlans;
    
    /**
     * @ORM\OneToMany(targetEntity=ShoppingList::class, mappedBy="user", orphanRemoval=true)
     */
    private $shoppingList;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $facebookId;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $googleId;


    /**
     * 
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $profilePictureFile
     */
    public function setProfilePictureFile(?File $profilePictureFile = null): void
    {
        $this->profilePictureFile = $profilePictureFile;

        if (null !== $profilePictureFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getProfilePictureFile(): ?File
    {
        return $this->profilePictureFile;
    }

    /**
     * @param string|null $profilePictureName
     * 
     */

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

    public function getSalt()
    {
        return null;
    }

    public function getRoles(): array
    {
        return array('ROLE_USER');
    }

    public function eraseCredentials()
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
     * @return Collection|Recipe[]
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
        if ($this->recipes->removeElement($recipe)) {
            // set the owning side to null (unless already changed)
            if ($recipe->getUserId() === $this) {
                $recipe->setUserId(null);
            }
        }

        return $this;
    }


    public function addLikedRecipe(Recipe $likedRecipe): self
    {
        if (!$this->likedRecipes->contains($likedRecipe)) {
            $this->likedRecipes[] = $likedRecipe;
        }

        return $this;
    }

    public function removeLikedRecipe(Recipe $likedRecipe): self
    {
        $this->likedRecipes->removeElement($likedRecipe);

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
            $weeklyPlan->setUser($this);
        }

        return $this;
    }

    public function removeWeeklyPlan(WeeklyPlan $weeklyPlan): self
    {
        if ($this->weeklyPlans->removeElement($weeklyPlan)) {
            // set the owning side to null (unless already changed)
            if ($weeklyPlan->getUser() === $this) {
                $weeklyPlan->setUser(null);
            }
        }

        return $this;
    }

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

    public function getGoogleId(): ?string
    {
        return $this->googleId;
    }

    public function setGoogleId(?string $googleId): self
    {
        $this->googleId = $googleId;

        return $this;
    }
}
