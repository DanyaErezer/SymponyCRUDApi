<?php

namespace App\Entity;

use App\Repository\BookRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: BookRepository::class)]
#[UniqueEntity(
    fields: ['title', 'isbn'],
    message: 'Книга с таким названием и ISBN уже существует'
)]
#[UniqueEntity(
    fields: ['title', 'publicationYear'],
    message: 'Книга с таким названием и годом публикации уже существует'
)]
class Book
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['book:read'])]
    private string $title;

    #[ORM\Column]
    #[Groups(['book:read'])]
    private int $publicationYear;

    #[ORM\Column(length: 255)]
    #[Groups(['book:read'])]
    private string $edition;

    #[ORM\Column(length: 20)]
    #[Groups(['book:read'])]
    private string $isbn;

    #[ORM\Column]
    #[Groups(['book:read'])]
    private int $pageCount;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['book:read'])]
    private ?string $coverImage = null;

    #[ORM\ManyToOne(targetEntity: Author::class, inversedBy: 'books')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['book:read'])]
    private Author $author;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getPublicationYear(): ?int
    {
        return $this->publicationYear;
    }

    public function setPublicationYear(?int $publicationYear): static
    {
        $this->publicationYear = $publicationYear;

        return $this;
    }

    public function getEdition(): ?string
    {
        return $this->edition;
    }

    public function setEdition(?string $edition): static
    {
        $this->edition = $edition;

        return $this;
    }

    public function getIsbn(): ?string
    {
        return $this->isbn;
    }

    public function setIsbn(?string $isbn): static
    {
        $this->isbn = $isbn;

        return $this;
    }

    public function getPageCount(): ?int
    {
        return $this->pageCount;
    }

    public function setPageCount(?int $pageCount): static
    {
        $this->pageCount = $pageCount;

        return $this;
    }

    public function getCoverImage(): ?string
    {
        return $this->coverImage;
    }

    public function setCoverImage(?string $coverImage): static
    {
        $this->coverImage = $coverImage;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getAuthor(): ?Author
    {
        return $this->author;
    }

    public function setAuthor(?Author $author): static
    {
        $this->author = $author;

        return $this;
    }
}
