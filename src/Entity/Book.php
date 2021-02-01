<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Controller\EditBookAction;
use App\Repository\BookRepository;
use App\Controller\CreateBookAction;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Vich\UploaderBundle\Mapping\Annotation\Uploadable;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 * @ApiResource(
*collectionOperations={
* "get",
* "post"={
 *             "controller"=CreateBookAction::class,
 *               "deserialize"=false,
 *             "security"="is_granted('ROLE_ADMIN')",
 *             "validation_groups"={"Default"},
 *             "openapi_context"={
 *                 "requestBody"={
 *                     "content"={
 *                         "multipart/form-data"={
 *                             "schema"={
 *                                 "type"="object",
 *                                 "properties"={
 *                                     "image"={
 *                                         "type"="string",
 *                                         "format"="binary"
 *                                     }
 *                                 }
 *                             }
 *                         }
 *                     }
 *                 }
 *             }
 *}
* },
* itemOperations={
* "patch",
* "get",
* "put"={
 *             "controller"=EditBookAction::class,
 *               "deserialize"=false,
 *             "security"="is_granted('ROLE_ADMIN')",
 *             "validation_groups"={"Default"},
 *             "openapi_context"={
 *                 "requestBody"={
 *                     "content"={
 *                         "multipart/form-data"={
 *                             "schema"={
 *                                 "type"="object",
 *                                 "properties"={
 *                                     "image"={
 *                                         "type"="string",
 *                                         "format"="binary"
 *                                     }
 *                                 }
 *                             }
 *                         }
 *                     }
 *                 }
 *             }
 *},
* "delete"={"security"="is_granted('ROLE_ADMIN')"}
*})
* @ApiFilter(SearchFilter::class, properties={"title": "partial"})
 * @ApiResource(attributes={"order"={"title"="DESC","price"="DESC","author"="DESC"}})
* @Vich\Uploadable
 * @ORM\Entity(repositoryClass=BookRepository::class)
 * 
 */
class Book
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * 
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     * 
     */
    private $title;

    /**
     * @ORM\Column(type="float",nullable=true)
     * 
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     *
     */
    private $author;

    /**
     * @ORM\ManyToMany(targetEntity=Category::class, mappedBy="products",cascade={"persist"})
     *
     * 
     */
    private $categories;

    /**
     * @var File|null
     *
     * @Vich\UploadableField(mapping="media_object")
     */
    private $image;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $imageName;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(string $author): self
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return Collection|Category[]
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
            $category->addProduct($this);
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        if ($this->categories->removeElement($category)) {
            $category->removeProduct($this);
        }

        return $this;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage(?File $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function setImageName(?string $imageName): self
    {
        $this->imageName = $imageName;

        return $this;
    }
}
