<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ApiResource(
 *collectionOperations={
* "get",
* "post"={"security"="is_granted('ROLE_ADMIN')"}
* },
* itemOperations={
* "get",
* "patch"={"security"="is_granted('ROLE_ADMIN')"},
* "delete"={"security"="is_granted('ROLE_ADMIN')"}
*})
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 * @UniqueEntity( fields={"label"},message="Label already exists")
 */
class Category
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $label;

    /**
     * @ORM\ManyToMany(targetEntity=Book::class, inversedBy="categories")
     */
    private $products;

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return Collection|Book[]
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Book $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
        }

        return $this;
    }

    public function removeProduct(Book $product): self
    {
        $this->products->removeElement($product);

        return $this;
    }
}
