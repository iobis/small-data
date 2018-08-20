<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;




/**
 * @ORM\Entity(repositoryClass="App\Repository\InputterRepository")
 * @UniqueEntity(
 *     fields={"email"},
 *     message= "The email is already taken"
 * )
 */
class Inputter implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;


    //assert set to loose (string validation), for other email validations, see http://symfony.com/doc/current/reference/constraints/Email.html
    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Email( message = "The email '{{ value }}' is not a valid email format.")
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min="8", minMessage = "At least 8 characters")
     *
     */
    private $password;

    /**
     * @Assert\EqualTo(propertyPath="password", message="please enter the same password")
     */
    public $confirm_password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastName;

    /**
     * @ORM\Column(type="json_array")
     */
    private $roles;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Occurrence", mappedBy="inputter")
     */
    private $occurrences;

    public function __construct()
    {
        $this->occurrences = new ArrayCollection();
        $this->roles[] = 'ROLE_INPUTTER';
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

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }


    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    public function getRoles()
    {
        $roles = $this->roles;
        //alternative: make a construct function
        if(!in_array('ROLE_INPUTTER', $roles)) {
            $roles[]='ROLE_INPUTTER';
        }
        return $roles;
    }

    public function setRoles($roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @return Collection|Occurrence[]
     */
    public function getOccurrences(): Collection
    {
        return $this->occurrences;
    }

    public function addOccurrence(Occurrence $occurrence): self
    {
        if (!$this->occurrences->contains($occurrence)) {
            $this->occurrences[] = $occurrence;
            $occurrence->setInputter($this);
        }

        return $this;
    }

    public function removeOccurrence(Occurrence $occurrence): self
    {
        if ($this->occurrences->contains($occurrence)) {
            $this->occurrences->removeElement($occurrence);
            // set the owning side to null (unless already changed)
            if ($occurrence->getInputter() === $this) {
                $occurrence->setInputter(null);
            }
        }

        return $this;
    }




}
