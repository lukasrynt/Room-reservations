<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;

/**
 * @ORM\Entity(repositoryClass=ReservationRepository::class)
 * @ExclusionPolicy("all")
 */
class Reservation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Expose
     */
    private ?int $id;

    /**
     * @ORM\Column(type="datetime")
     * @Expose
     */
    private $date;

    /**
     * @ORM\Column(type="time")
     * @Expose
     * @Assert\Expression(
     *     "this.getTimeTo() <= this.getRoom().getOpenedTo()",
     *     message="The room is not opened at this time!",
     * )
     * @Assert\Expression(
     *     "this.getTimeTo() >= this.getTimeFrom()",
     *     message="The end of reservation time must be before its start!",
     * )
     */
    private $timeTo;

    /**
     * @ORM\Column(type="time")
     * @Expose
     * @Assert\Expression(
     *     "this.getTimeFrom() >= this.getRoom().getOpenedFrom()",
     *     message="The room is not opened at this time!",
     * )
     */
    private $timeFrom;

    /**
     * @ORM\Column(type="enum_state_type", options={"default": "PENDING"}, length=255, nullable=false)
     * @Expose
     */
    private States $state;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="reservations")
     * @ORM\JoinColumn(nullable=false)
     * @Expose
     */
    private User $user;

    /**
     * @ORM\ManyToOne(targetEntity=Room::class, inversedBy="reservations")
     * @ORM\JoinColumn(nullable=false)
     * @Expose
     */
    private Room $room;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="reservationsToAttend")
     * @Expose
     * @Assert\Expression(
     *     "this.getRoom().getCapacity() >= this.getAttendees().count()",
     *     message="The capacity of selected room is exceeded!",
     * )
     */
    private Collection $attendees;

    public function __construct()
    {
        $this->attendees = new ArrayCollection();
        $this->state = new States(States::PENDING);
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDate(): ?string
    {
        if (!$this->date) {
            return null;
        }
        return $this->date->format('Y-m-d');
    }

    public function setDate(string $date): self
    {
        try {
            $this->date = new DateTime($date);
        } catch (\Exception $e) {
            print($e);
        }
        return $this;
    }

    public function getTimeTo(): ?string
    {
        if (!$this->timeTo) {
            return null;
        }
        return $this->timeTo->format('H:i:s');
    }

    public function setTimeTo(string $timeTo): self
    {
        try {
            $this->timeTo = new DateTime($timeTo);
        } catch (\Exception $e) {
            print($e);
        }
        return $this;
    }

    public function getTimeFrom(): ?string
    {
        if (!$this->timeFrom) {
            return null;
        }
        return $this->timeFrom->format('H:i:s');
    }

    public function setTimeFrom(string $timeFrom): self
    {
        try {
            $this->timeFrom = new DateTime($timeFrom);
        } catch (\Exception $e) {
            print($e);
        }
        return $this;
    }
}
