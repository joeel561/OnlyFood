<?php

namespace App\Entity;

use App\Repository\ShoppingListRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ShoppingListRepository::class)]
class ShoppingList
{
    #[Groups(['weekly_plan', 'shopping_list'])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[Groups(['weekly_plan', 'shopping_list'])]
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $name = null;

    #[Groups(['shopping_list'])]
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'shoppingList')]
    private ?User $user = null;

    #[Groups(['shopping_list'])]
    #[ORM\Column(type: 'json', nullable: true)]
    private ?array $ingredients = [];


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

    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getIngredients(): ?array
    {
        return $this->ingredients;
    }

    public function setIngredients(?array $ingredients): self
    {
        $this->ingredients = $ingredients;

        return $this;
    }
}
