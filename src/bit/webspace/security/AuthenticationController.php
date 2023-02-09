<?php

namespace App\bit\webspace\security;

use App\bit\webspace\rest\RestController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class AuthenticationController extends RestController {
  #[Route('/api/auth', name: 'authenticate', methods: ['POST'])]
  public function authenticate(#[CurrentUser] ?User $user): JsonResponse {
    $this->logger->debug('AuthenticationController->authenticate', ['user' => $user->getUserIdentifier()]);
    return $this->json(['message' => 'Test']);
  }

  #[Route('/api/auth', name: 'testAuth', methods: ['GET'])]
  public function test(UserPasswordHasherInterface $passwordHasher): JsonResponse {
    $user = new User();
    return $this->json(['test' => $passwordHasher->hashPassword($user,'test')]);
  }
}
