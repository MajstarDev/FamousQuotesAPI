<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AuthorRepository")
 */
class Author
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
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Quote", mappedBy="author", orphanRemoval=true)
     */
    private $quote;

    public function __construct()
    {
        $this->quote = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|Quote[]
     */
    public function getQuote(): Collection
    {
        return $this->quote;
    }

    public function addQuote(Quote $quote): self
    {
        if (!$this->quote->contains($quote)) {
            $this->quote[] = $quote;
            $quote->setAuthor($this);
        }

        return $this;
    }

    public function removeQuote(Quote $quote): self
    {
        if ($this->quote->contains($quote)) {
            $this->quote->removeElement($quote);
            // set the owning side to null (unless already changed)
            if ($quote->getAuthor() === $this) {
                $quote->setAuthor(null);
            }
        }

        return $this;
    }
}
