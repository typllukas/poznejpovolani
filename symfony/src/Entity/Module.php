<?php

namespace App\Entity;

use App\Repository\ModuleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ModuleRepository::class)]
class Module
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $module = null;

    #[ORM\ManyToMany(targetEntity: Webinar::class, mappedBy: 'modules')]
    private Collection $webinars;

    #[ORM\ManyToMany(targetEntity: Registrant::class, inversedBy: 'modules')]
    private Collection $registrants;

    #[ORM\Column(length: 255)]
    private ?string $abbreviation = null;

    #[ORM\Column(length: 500, nullable: true)]
    private ?string $description = null;

    public function __construct()
    {
        $this->webinars = new ArrayCollection();
        $this->registrants = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getModule(): ?string
    {
        return $this->module;
    }

    public function setModule(string $module): static
    {
        $this->module = $module;

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
            $webinar->addModule($this);
        }

        return $this;
    }

    public function removeWebinar(Webinar $webinar): static
    {
        if ($this->webinars->removeElement($webinar)) {
            $webinar->removeModule($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Registrant>
     */
    public function getRegistrants(): Collection
    {
        return $this->registrants;
    }

    public function addRegistrant(Registrant $registrant): static
    {
        if (!$this->registrants->contains($registrant)) {
            $this->registrants->add($registrant);
        }

        return $this;
    }

    public function removeRegistrant(Registrant $registrant): static
    {
        $this->registrants->removeElement($registrant);

        return $this;
    }

    public function getAbbreviation(): ?string
    {
        return $this->abbreviation;
    }

    public function setAbbreviation(string $abbreviation): static
    {
        $this->abbreviation = $abbreviation;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }
}
