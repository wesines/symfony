<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: UserRepository::class)]
class User
{
    /**
     * @groups("user:read")
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    /**
     * @groups("user:read")
     * @Assert\NotBlank(message="Le username est obligatoire")
     */
    #[ORM\Column(length: 255)]
    private ?string $username = null;
    /**
     * @groups("user:read")
     * @Assert\NotBlank(message="Le mot de passe est obligatoire")
     */
    #[ORM\Column(length: 255)]
    private ?string $password = null;

    /**
     * @groups("user:read")
     * @Assert\NotBlank(message="La ville est obligatoire")
     */
    #[ORM\Column(length: 255)]
    private ?string $city = null;
    /**
     * @groups("user:read")
     * @Assert\NotBlank(message="La position du device est obligatoire")
     */

    #[ORM\Column]
    private ?string $lat = null;

    #[ORM\Column]
    private ?string $lng = null;

    #[ORM\Column(length: 255)]
    private ?string $device = null;

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



    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }



    public function getLat(): ?string
    {
        return $this->lat;
    }

    public function setLat(string $lat): self
    {
        $this->lat = $lat;

        return $this;
    }

    public function getLng(): ?string
    {
        return $this->lng;
    }

    public function setLng(string $lng): self
    {
        $this->lng = $lng;

        return $this;
    }

    public function getDevice(): ?string
    {
        return $this->device;
    }

    public function setDevice(string $device): self
    {
        $this->device = $device;

        return $this;
    }
}