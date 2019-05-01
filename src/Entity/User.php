<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $access_key;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Quote", mappedBy="user", orphanRemoval=true)
     */
    private $quotes;

    public function __construct()
    {
        $this->quotes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAccessKey(): ?string
    {
        return $this->access_key;
    }

    public function setAccessKey(string $access_key): self
    {
        $this->access_key = $access_key;

        return $this;
    }

    /**
     * @return Collection|Quote[]
     */
    public function getQuotes(): Collection
    {
        return $this->quotes;
    }

    public function addQuote(Quote $quote): self
    {
        if (!$this->quotes->contains($quote)) {
            $this->quotes[] = $quote;
            $quote->setUser($this);
        }

        return $this;
    }

    public function removeQuote(Quote $quote): self
    {
        if ($this->quotes->contains($quote)) {
            $this->quotes->removeElement($quote);
            // set the owning side to null (unless already changed)
            if ($quote->getUser() === $this) {
                $quote->setUser(null);
            }
        }

        return $this;
    }

	/* implementing methods from UserInterface which we don't really need in our app */
    public function getRoles()
    {
	return array('ROLE_USER');
    }
    public function getPassword()
    {
    }
    public function getSalt()
    {
    }
    public function getUsername()
    {
    }
    public function isEnabled()
    {
    }
    public function eraseCredentials()
    {
    }

}