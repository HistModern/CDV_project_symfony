<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use App\Security\UserAuthenticator;
use App\Repository\UserRepository;

#[Route('/api/login', name: 'api_login', methods: ['POST'])]
class LoginController
{
    public function __invoke(Request $request, UserAuthenticatorInterface $authenticator, UserRepository $userRepository)
    {
        return new JsonResponse(['message' => 'Login successful'], 200);
    }
}
