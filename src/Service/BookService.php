<?php

namespace App\Service;

use App\Entity\Author;
use App\Entity\Book;
use App\Repository\BookRepository;
use App\Validator\BookValidator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Serializer\SerializerInterface;

class BookService
{
    public function __construct(
        private BookRepository         $bookRepository,
        private EntityManagerInterface $entityManager,
        private BookValidator          $bookValidator,
        private string                 $coverImageDirectory,
        private SerializerInterface    $serializer
    )
    {
    }

    public function createBook(array $data, ?UploadedFile $coverImage = null): JsonResponse
    {
        if (isset($data['author']['id'])) {
            $authorId = $data['author']['id'];
        } elseif (isset($data['authorId'])) {
            $authorId = $data['authorId'];
        } else {
            return new JsonResponse(['error' => 'Author ID is required'], 400);
        }


        $author = $this->entityManager->getRepository(Author::class)->find($authorId);
        if (!$author) {
            return new JsonResponse(['error' => 'Author not found'], 404);
        }

        $book = new Book();
        $book->setTitle($data['title']);
        $book->setPublicationYear($data['publicationYear']);
        $book->setEdition($data['edition']);
        $book->setIsbn($data['isbn']);
        $book->setPageCount($data['pageCount']);
        $book->setAuthor($author);

        if ($coverImage) {
            $filename = uniqid() . '.' . $coverImage->guessExtension();
            $coverImage->move($this->coverImageDirectory, $filename);
            $book->setCoverImage($filename);
        }

        $errors = $this->bookValidator->validate($book);
        if (count($errors) > 0) {
            return new JsonResponse(['errors' => $errors], 400);
        }

        $this->entityManager->persist($book);
        $this->entityManager->flush();

        $json = $this->serializer->serialize($book, 'json', ['groups' => 'book:read']);
        return new JsonResponse($json, 201, [], true);
    }

    public function updateBook(int $id, array $data, ?UploadedFile $coverImage = null): JsonResponse
    {
        $book = $this->bookRepository->find($id);
        if (!$book) {
            return new JsonResponse(['error' => 'Book not found'], 404);
        }

        $book->setTitle($data['title'] ?? $book->getTitle());
        $book->setPublicationYear($data['publicationYear'] ?? $book->getPublicationYear());
        $book->setEdition($data['edition'] ?? $book->getEdition());
        $book->setIsbn($data['isbn'] ?? $book->getIsbn());
        $book->setPageCount($data['pageCount'] ?? $book->getPageCount());

        if ($coverImage) {
            if ($book->getCoverImage()) {
                $oldImage = $this->coverImageDirectory . '/' . $book->getCoverImage();
                if (file_exists($oldImage)) {
                    unlink($oldImage);
                }
            }

            $filename = uniqid() . '.' . $coverImage->guessExtension();
            $coverImage->move($this->coverImageDirectory, $filename);
            $book->setCoverImage($filename);
        }

        if (isset($data['authorId'])) {
            $author = $this->entityManager->getRepository(Author::class)->find($data['authorId']);
            if ($author) {
                $book->setAuthor($author);
            }
        }

        $errors = $this->bookValidator->validate($book);
        if (count($errors) > 0) {
            return new JsonResponse(['errors' => $errors], 400);
        }

        $this->entityManager->flush();

        $json = $this->serializer->serialize($book, 'json', ['groups' => 'book:read']);

        return new JsonResponse($json, 200, [], true);
    }

    public function deleteBook(int $id): JsonResponse
    {
        $book = $this->bookRepository->find($id);
        if (!$book) {
            return new JsonResponse(['error' => 'Book not found'], 404);
        }

        if ($book->getCoverImage()) {
            $imagePath = $this->coverImageDirectory . '/' . $book->getCoverImage();
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        $this->entityManager->remove($book);
        $this->entityManager->flush();

        return new JsonResponse(null, 204);
    }

    public function getBook(int $id): JsonResponse
    {
        $book = $this->bookRepository->find($id);
        if (!$book) {
            return new JsonResponse(['error' => 'Book not found'], 404);
        }

        $json = $this->serializer->serialize($book, 'json', ['groups' => 'book:read']);
        return new JsonResponse($json, 200, [], true);
    }

    public function getAllBooks(): JsonResponse
    {
        $books = $this->bookRepository->findAll();
        $json = $this->serializer->serialize($books, 'json', ['groups' => 'book:read']);
        return new JsonResponse($json, 200, [], true);
    }
}
