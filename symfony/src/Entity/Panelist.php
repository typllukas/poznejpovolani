<?php

namespace App\Entity;

use App\Repository\PanelistRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PanelistRepository::class)]
class Panelist
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\ManyToMany(targetEntity: Webinar::class, mappedBy: 'panelists')]
    private Collection $webinars;

    public function __construct()
    {
        $this->webinars = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

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
            $webinar->addPanelist($this);
        }

        return $this;
    }

    public function removeWebinar(Webinar $webinar): static
    {
        if ($this->webinars->removeElement($webinar)) {
            $webinar->removePanelist($this);
        }

        return $this;
    }
    
}
