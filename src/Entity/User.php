<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 * @method string getUserIdentifier()
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({"user" = "User", "admin" = "Admin", "roomManager" = "RoomManager",
 *                          "groupManager" = "GroupManager"})
 * @ExclusionPolicy("all")
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    const COMMON_USER = 'ROLE_USER';
    const ADMIN = 'ROLE_ADMIN';
    const GROUP_ADMIN = 'ROLE_GROUP_ADMIN';
    const ROOM_ADMIN = 'ROLE_ROOM_ADMIN';

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Expose
     */
    protected int $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Expose
     */
    protected string $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Expose
     */
    protected string $lastName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Expose
     */
    protected string $email;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Expose
     */
    protected int $phoneNumber;

    /**
     * @ORM\Column(type="json")
     * @Expose
     * @Assert\Expression(
     *     "this.getRolesCount() <= 1",
     *     message="User is allowed to have only ONE role!",
     * )
     */
    protected array $roles = [];

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Expose
     */
    protected string $note;

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    protected string $password;


    /**
     * @ORM\Column(type="string", length=100, nullable=false)
     * @Expose
     */
    protected string $username;

    /**
     * @ORM\ManyToMany(targetEntity=Room::class, mappedBy="users")
     */
    protected Collection $rooms;

    /**
     * @ORM\OneToMany(targetEntity=Reservation::class, mappedBy="user")
     */
    protected Collection $reservations;

    /**
     * @ORM\ManyToOne(targetEntity=Group::class, inversedBy="members")
     */
    private ?Group $group;

    /**
     * @ORM\ManyToMany(targetEntity=Reservation::class, mappedBy="attendees")
     */
    private Collection $reservationsToAttend;

    public function __construct()
    {
        $this->rooms = new ArrayCollection();
        $this->reservations = new ArrayCollection();
        $this->reservationsToAttend = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

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

    public function getPhoneNumber(): ?int
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(int $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(string $note): self
    {
        $this->note = $note;

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

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        //$this->plainPassword = "";
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;

        if (empty($roles))
            $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function getRolesCount(): int
    {
        return count($this->roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @return Collection|Room[]
     */
    public function getRooms(): Collection
    {
        return $this->rooms;
    }

    public function addRoom(Room $room): self
    {
        if (!$this->rooms->contains($room)) {
            $this->rooms[] = $room;
            $room->addUser($this);
        }

        return $this;
    }

    public function removeRoom(Room $room): self
    {
        if ($this->rooms->removeElement($room)) {
            $room->removeUser($this);
        }

        return $this;
    }

    public function __toString()
    {
        return $this->firstName . " " . $this->lastName;
    }

    public function isAdmin(): Bool
    {
        return in_array(self::ADMIN, $this->roles);
    }

    public function isRoomAdmin(): Bool
    {
        return in_array(self::ROOM_ADMIN, $this->roles);
    }

    public function isGroupAdmin(): Bool
    {
        return in_array(self::GROUP_ADMIN, $this->roles);
    }

    public function isCommonUser(): Bool
    {
        return in_array(self::COMMON_USER, $this->roles);
    }

    /**
     * @return Collection|Reservation[]
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): self
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations[] = $reservation;
            $reservation->setUser($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): self
    {
        if ($this->reservations->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getUser() === $this) {
                $reservation->setUser(null);
            }
        }

        return $this;
    }

    public function getGroup(): ?Group
    {
        return $this->group;
    }

    public function setGroup(?Group $group): self
    {
        $this->group = $group;

        return $this;
    }

    /**
     * @return Collection|Reservation[]
     */
    public function getReservationsToAttend(): Collection
    {
        return $this->reservationsToAttend;
    }

    public function addReservationsToAttend(Reservation $reservationsToAttend): self
    {
        if (!$this->reservationsToAttend->contains($reservationsToAttend)) {
            $this->reservationsToAttend[] = $reservationsToAttend;
            $reservationsToAttend->addAttendee($this);
        }

        return $this;
    }

    public function removeReservationsToAttend(Reservation $reservationsToAttend): self
    {
        if ($this->reservationsToAttend->removeElement($reservationsToAttend)) {
            $reservationsToAttend->removeAttendee($this);
        }

        return $this;
    }
}
