<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @Vich\Uploadable
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"username"}, message="There is already an account with this username")
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"account_overview"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Groups({"account_overview"})
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255, unique=true))
     * @Groups({"account_overview"})
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
     * @Groups({"account_overview"})
     */
    private $profilePictureName;

    /**
     * @ORM\Column(type="integer", nullable=true)
     *
     * @var int|null
     */
    private $imageSize;

    /**
     * @ORM\Column(type="boolean", options={"default":"1"}, nullable=true)
     * @Groups({"account_overview"})
     */
    private $privatMode = true;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"account_overview"})
     */
    private $lightMode;


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

    public function getPrivatMode(): ?string
    {
        return $this->privatMode;
    }

    public function setPrivatMode(?string $privatMode): self
    {
        $this->privatMode = $privatMode;

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
    public function getUserIdentifier()
    {
        return $this->username;
    }

    public function getSalt()
    {
        return null;
    }

    public function getRoles()
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
}
