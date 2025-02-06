<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RegistrationController extends AbstractController
{
    #[Route('/api/register', name: 'app_api_register', methods: ['POST'])]
    public function register(
        Request $request,
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator
    ): Response {
        // Получаем данные из тела запроса
        $data = json_decode($request->getContent(), true);

        // Проверяем, что данные корректно переданы
        if (!$data || !isset($data['username'], $data['email'], $data['password'])) {
            return new JsonResponse(['error' => 'Invalid data. Please provide username, email, and password.'], 400);
        }

        // Создаем нового пользователя
        $user = new User();
        $user->setUsername($data['username']);
        $user->setEmail($data['email']);

        // Временно отключаем хеширование пароля (по вашему требованию)
        $user->setPassword($data['password']);

        // Валидация данных
        $errors = $validator->validate($user);
        if (count($errors) > 0) {
            $errorsArray = [];
            foreach ($errors as $error) {
                $errorsArray[] = $error->getMessage();
            }
            return new JsonResponse(['errors' => $errorsArray], 400);
        }

        try {
            // Сохраняем пользователя в базу данных
            $entityManager->persist($user);
            $entityManager->flush();

            // Возвращаем успешный ответ
            return new JsonResponse([
                'message' => 'User registered successfully',
                'user' => [
                    'id' => $user->getId(),
                    'username' => $user->getUsername(),
                    'email' => $user->getEmail(),
                    'created_at' => $user->getCreatedAt()->format('Y-m-d H:i:s'),
                ],
            ], 201);
        } catch (\Exception $e) {
            // Обработка ошибок базы данных
            return new JsonResponse(['error' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }

}