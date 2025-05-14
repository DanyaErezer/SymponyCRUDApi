<?php

namespace App\Controller;

use App\Service\BookService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/books', name: 'api_books_')]
class BookController extends AbstractController
{
    public function __construct(private BookService $bookService) {}

    #[Route('', name: 'create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->request->get('data'), true) ?? [];
        $coverImage = $request->files->get('coverImage');

        return $this->bookService->createBook($data, $coverImage);
    }

    #[Route('/{id}', name: 'update', methods: ['PUT'])]
    public function update(int $id, Request $request): JsonResponse
    {
        $data = json_decode($request->request->get('data'), true) ?? [];
        $coverImage = $request->files->get('coverImage');

        return $this->bookService->updateBook($id, $data, $coverImage);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        return $this->bookService->deleteBook($id);
    }

    #[Route('/{id}', name: 'get', methods: ['GET'])]
    public function get(int $id): JsonResponse
    {
        return $this->bookService->getBook($id);
    }

    #[Route('', name: 'list', methods: ['GET'])]
    public function list(): JsonResponse
    {
        return $this->bookService->getAllBooks();
    }
}
