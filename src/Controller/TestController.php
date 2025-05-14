<?php

namespace App\Controller;

use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    #[Route('/test-db', name: 'test_db')]
    public function test(Connection $connection): JsonResponse
    {
        try {
            $connection->executeQuery('SELECT 1');
            return new JsonResponse(['status' => 'OK', 'message' => 'Database connection successful']);
        } catch (\Exception $e) {
            return new JsonResponse(['status' => 'ERROR', 'message' => $e->getMessage()], 500);
        }
    }
}
