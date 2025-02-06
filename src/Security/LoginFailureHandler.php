<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;

class LoginFailureHandler implements AuthenticationFailureHandlerInterface
{
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): JsonResponse
    {
        // Возвращаем ответ, если аутентификация не удалась
        return new JsonResponse([
            'status' => 'error',
            'message' => 'Invalid credentials', // Тут можно выводить больше информации из исключения
        ], 401);  // Код 401 — ошибка авторизации
    }
}