<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReservationRepository::class)
 */
class Reservation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateTo;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateFrom;

    /**
     * @ORM\Column(type="enum_state_type", options={"default": "PENDING"}, length=255, nullable=false)
     */
    private States $state;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="reservations")
     * @ORM\JoinColumn(nullable=false)
     */
    private User $user;

    /**
     * @ORM\ManyToOne(targetEntity=Room::class, inversedBy="reservations")
     * @ORM\JoinColumn(nullable=false)
     */
    private Room $room;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="reservationsToAttend")
     */
    private Collection $attendees;

    public function __construct()
    {
        $this->attendees = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateTo(): ?string
    {
        if (!$this->dateTo) {
            return null;
        }
        return $this->dateTo->format('Y-m-d H:i');
    }

    public function setDateTo(string $dateTo): self
    {
        try {
            $this->dateTo = new DateTime($dateTo);
        } catch (\Exception $e) {
            print($e);
        }

        return $this;
    }

    public function getDateFrom(): ?string
    {
        if (!$this->dateFrom) {
            return null;
        }
        return $this->dateFrom->format('Y-m-d H:i');
    }

    public function setDateFrom(string $dateFrom): self
    {
        try {
            $this->dateFrom = new DateTime($dateFrom);
        } catch (\Exception $e) {
            print($e);
        }

        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getRoom(): Room
    {
        return $this->room;
    }

    public function setRoom(Room $room): self
    {
        $this->room = $room;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getAttendees(): Collection
    {
        return $this->attendees;
    }

    public function addAttendee(User $attendee): self
    {
        if (!$this->attendees->contains($attendee)) {
            $this->attendees[] = $attendee;
        }

        return $this;
    }

    public function removeAttendee(User $attendee): self
    {
        $this->attendees->removeElement($attendee);

        return $this;
    }

    public function getState(): States
    {
        return $this->state;
    }

    public function setState($state): self
    {
        $this->state = $state;

        return $this;
    }

    public function isPending(): bool
    {
        return $this->state == States::PENDING;
    }

    public function isApproved(): bool
    {
        return $this->state == States::APPROVED;
    }

    public function isRejected(): bool
    {
        return $this->state == States::REJECTED;
    }
}
