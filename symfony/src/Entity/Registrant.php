<?php

namespace App\Entity;

use App\Repository\RegistrantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RegistrantRepository::class)]
class Registrant
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $firstName = null;

    #[ORM\Column(length: 255)]
    private ?string $lastName = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\ManyToMany(targetEntity: Webinar::class, mappedBy: 'registrants')]
    private Collection $webinars;

    #[ORM\ManyToMany(targetEntity: Module::class, mappedBy: 'registrants')]
    private Collection $modules;

    public function __construct()
    {
        $this->webinars = new ArrayCollection();
        $this->modules = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return Collection<int, Webinar>
     */
    public function getWebinars(): Collection
    {
        return $this->webinars;
    }

    public function addWebinar(Webinar $webinar): static
    {
        if (!$this->webinars->contains($webinar)) {
            $this->webinars->add($webinar);
            $webinar->addRegistrant($this);
        }

        return $this;
    }

    public function removeWebinar(Webinar $webinar): static
    {
        if ($this->webinars->removeElement($webinar)) {
            $webinar->removeRegistrant($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Module>
     */
    public function getModules(): Collection
    {
        return $this->modules;
    }

    public function addModule(Module $module): static
    {
        if (!$this->modules->contains($module)) {
            $this->modules->add($module);
            $module->addRegistrant($this);
        }

        return $this;
    }

    public function removeModule(Module $module): static
    {
        if ($this->modules->removeElement($module)) {
            $module->removeRegistrant($this);
        }

        return $this;
    }
}
