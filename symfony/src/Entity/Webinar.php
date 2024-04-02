<?php

namespace App\Entity;

use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\WebinarRepository;
use Doctrine\DBAL\Types\BigIntType;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: WebinarRepository::class)]
class Webinar
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Topic = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $Organization = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank()]
    private ?\DateTimeInterface $Date = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    #[Assert\NotBlank()]
    private ?\DateTimeInterface $Time = null;

    #[ORM\Column(type: "integer", nullable: true)]
    // #[Assert\NotBlank()]
    private ?int $Duration = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    // #[Assert\NotBlank()]
    private $Banner = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    // #[Assert\NotBlank()]
    private ?string $ReminderText = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    // #[Assert\NotBlank()]
    private ?string $RecordingLink = null;

    #[ORM\ManyToMany(targetEntity: Module::class, inversedBy: 'webinars')]
    private Collection $modules;

    #[ORM\ManyToMany(targetEntity: Registrant::class, inversedBy: 'webinars')]
    private Collection $registrants;

    #[ORM\ManyToMany(targetEntity: Panelist::class, inversedBy: 'webinars', cascade: ['persist'])]
    private Collection $panelists;

    #[ORM\Column(type: "bigint", nullable: true)]
    private ?int $zoomId = null;

    public function __construct()
    {
        $this->modules = new ArrayCollection();
        $this->registrants = new ArrayCollection();
        $this->panelists = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTopic(): ?string
    {
        return $this->Topic;
    }

    public function setTopic(string $Topic): static
    {
        $this->Topic = $Topic;

        return $this;
    }

    public function getOrganization(): ?string
    {
        return $this->Organization;
    }

    public function setOrganization(string $Organization): static
    {
        $this->Organization = $Organization;

        return $this;
    }

    public function getDate(): ?DateTimeInterface
    {
        return $this->Date;
    }

    public function setDate(\DateTimeInterface $Date): self
    {
        $this->Date = $Date;

        return $this;
    }

    public function getTime(): ?\DateTimeInterface
    {
        return $this->Time;
    }

    public function setTime(\DateTimeInterface $Time): self
    {
        $this->Time = $Time;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->Duration;
    }

    public function setDuration(int $Duration): static
    {
        $this->Duration = $Duration;

        return $this;
    }

    public function getBanner(): ?string
    {
        return $this->Banner;
    }

    public function setBanner(?string $Banner): self
    {
        $this->Banner = $Banner;

        return $this;
    }

    public function getReminderText(): ?string
    {
        return $this->ReminderText;
    }

    public function setReminderText(?string $ReminderText): static
    {
        $this->ReminderText = $ReminderText;

        return $this;
    }

    public function getRecordingLink(): ?string
    {
        return $this->RecordingLink;
    }

    public function setRecordingLink(?string $RecordingLink): static
    {
        $this->RecordingLink = $RecordingLink;

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
        }

        return $this;
    }

    public function removeModule(Module $module): static
    {
        $this->modules->removeElement($module);

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

    /**
     * @return Collection<int, Panelist>
     */
    public function getPanelists(): Collection
    {
        return $this->panelists;
    }

    public function addPanelist(Panelist $panelist): static
    {
        if (!$this->panelists->contains($panelist)) {
            $this->panelists->add($panelist);
        }

        return $this;
    }

    public function removePanelist(Panelist $panelist): static
    {
        $this->panelists->removeElement($panelist);

        return $this;
    }

    public function getZoomId(): ?int
    {
        return $this->zoomId;
    }

    public function setZoomId(?int $zoomId): static
    {
        $this->zoomId = $zoomId;

        return $this;
    }
}
