<?php

namespace App\Controller;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class RestController extends DefaultController {

  public function __construct(
    protected LoggerInterface     $logger,
    protected SerializerInterface $serializer,
    protected ValidatorInterface  $validator,
  ) {
    parent::__construct($this->logger);
  }

  protected function toEntity(Request $request, string $type) {
    return $this->serializer->deserialize($request->getContent(), $type, 'json');
  }

  protected function violationsToJson(ConstraintViolationListInterface $violations): JsonResponse {
    $result = [
      'violations' => []
    ];
    foreach ($violations as $violation) {
      $result['violations'][] = [
        'invalidValue' => $violation->getInvalidValue(),
        'message' => $violation->getMessage(),
        'propertyPath' => $violation->getPropertyPath(),
      ];
    }
    return $this->toJson($result, 400);
  }

  protected function toJson($entity, $status = 200, $headers = []): JsonResponse {
    return $this->json($entity, $status, $headers, [
      AbstractObjectNormalizer::SKIP_NULL_VALUES => true,
      AbstractObjectNormalizer::SKIP_UNINITIALIZED_VALUES => true,
    ]);
  }
}
