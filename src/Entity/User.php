<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 * @method string getUserIdentifier()
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({"user" = "User", "admin" = "Admin", "roomManager" = "RoomManager",
 *                          "groupManager" = "GroupManager"})
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected int $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected string $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected string $lastName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected string $email;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected int $phoneNumber;

    /**
     * @ORM\Column(type="json")
     */
    protected array $roles = [];

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected string $note;

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    protected string $password;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected string $plainPassword;

    /**
     * @ORM\Column(type="string", length=100, nullable=false)
     */
    protected string $username;

    /**
     * @ORM\Column(type="enum_roles_type", length=255, nullable=true)
     */
    protected Roles $role;

    /**
     * @ORM\ManyToMany(targetEntity=Room::class, mappedBy="users")
     */
    protected Collection $rooms;

    /**
     * @ORM\OneToMany(targetEntity=Request::class, mappedBy="requestor")
     */
    protected Collection $requests;

    /**
     * @ORM\OneToMany(targetEntity=Reservation::class, mappedBy="user")
     */
    protected Collection $reservations;

    /**
     * @ORM\ManyToOne(targetEntity=Group::class, inversedBy="members")
     * @ORM\JoinColumn(nullable=false)
     */
    private Group $group;

    public function __construct()
    {
        $this->rooms = new ArrayCollection();
        $this->requests = new ArrayCollection();
        $this->reservations = new ArrayCollection();
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

    public function setPhoneNumber(?int $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }


    public function getRole(): Roles
    {
        return $this->role;
    }

    public function setRole(Roles $role): self
    {
        $this->role = $role;

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
     * @return string|null
     */
    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    /**
     * @param string $plainPassword
     */
    public function setPlainPassword(string $plainPassword): void
    {
        $this->plainPassword = $plainPassword;
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
        $this->plainPassword = "";
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
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
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

    /**
     * @return Collection
     */
    public function getRequests(): Collection
    {
        return $this->requests;
    }

    public function addRequest(Request $request): self
    {
        if (!$this->requests->contains($request)) {
            $this->requests[] = $request;
            $request->setRequestor($this);
        }

        return $this;
    }

    public function removeRequest(Request $request): self
    {
        if ($this->requests->removeElement($request)) {
            // set the owning side to null (unless already changed)
            if ($request->getRequestor() === $this) {
                $request->setRequestor(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->firstName . " " . $this->lastName;
    }

    public function isAdmin(): Bool
    {
        return $this->role == Roles::ADMIN;
    }

    public function isRoomAdmin(): Bool
    {
        return $this->role == Roles::ROOM_ADMIN;
    }

    public function isGroupAdmin(): Bool
    {
        return $this->role == Roles::GROUP_ADMIN;
    }

    public function isCommonUser(): Bool
    {
        return $this->role == Roles::COMMON_USER;
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
}
