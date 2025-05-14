<?php

namespace App\Service;

use App\Entity\Author;
use App\Repository\AuthorRepository;
use App\Validator\AuthorValidator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;

class AuthorService
{
    public function __construct(
        private AuthorRepository $authorRepository,
        private EntityManagerInterface $entityManager,
        private AuthorValidator $authorValidator,
        private SerializerInterface $serializer
    ) {}

    public function createAuthor(array $data): JsonResponse
    {
        $author = new Author();
        $author->setLastName($data['lastName']);
        $author->setFirstName($data['firstName']);
        $author->setMiddleName($data['middleName'] ?? null);

        $errors = $this->authorValidator->validate($author);
        if (count($errors) > 0) {
            return new JsonResponse(['errors' => $errors], 400);
        }

        $this->entityManager->persist($author);
        $this->entityManager->flush();
        $json = $this->serializer->serialize($author, 'json', ['groups' => 'author:read']);

        return new JsonResponse($json, 201, [], true);
    }

    public function updateAuthor(int $id, array $data): JsonResponse
    {
        $author = $this->authorRepository->find($id);
        if (!$author) {
            return new JsonResponse(['error' => 'Author not found'], 404);
        }

        $author->setLastName($data['lastName'] ?? $author->getLastName());
        $author->setFirstName($data['firstName'] ?? $author->getFirstName());
        $author->setMiddleName($data['middleName'] ?? $author->getMiddleName());

        $errors = $this->authorValidator->validate($author);
        if (count($errors) > 0) {
            return new JsonResponse(['errors' => $errors], 400);
        }

        $this->entityManager->flush();
        $json = $this->serializer->serialize($author, 'json', ['groups' => 'author:read']);

        return new JsonResponse($json, 200, [], true);
    }

    public function deleteAuthor(int $id): JsonResponse
    {
        $author = $this->authorRepository->find($id);
        if (!$author) {
            return new JsonResponse(['error' => 'Author not found'], 404);
        }

        $this->entityManager->remove($author);
        $this->entityManager->flush();

        return new JsonResponse(null, 204);
    }

    public function getAuthor(int $id): JsonResponse
    {
        $author = $this->authorRepository->find($id);
        if (!$author) {
            return new JsonResponse(['error' => 'Author not found'], 404);
        }

        $json = $this->serializer->serialize($author, 'json', ['groups' => 'author:read']);

        return new JsonResponse($json, 200, [], true);
    }

    public function getAllAuthors(): JsonResponse
    {
        $authors = $this->authorRepository->findAll();
        $json = $this->serializer->serialize($authors, 'json', ['groups' => 'author:read']);

        return new JsonResponse($json, 200, [], true);
    }
}