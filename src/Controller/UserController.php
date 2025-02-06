<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/users', name: 'api_users_')]
class UserController extends AbstractController
{
    #[Route('', name: 'list', methods: ['GET'])]
    public function list(EntityManagerInterface $entityManager, SerializerInterface $serializer): JsonResponse
    {
        // Получаем всех пользователей
        $users = $entityManager->getRepository(User::class)->findAll();
        
        // Сериализуем данные с учетом группы 'user:read'
        $json = $serializer->serialize($users, 'json', ['groups' => 'user:read']);

        // Возвращаем JSON-ответ
        return new JsonResponse($json, 200, [], true);
    }
}
