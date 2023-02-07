<?php

namespace App\bit\webspace\rest;

use App\bit\webspace\DefaultController;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

abstract class RestController extends DefaultController {

  public function __construct(
    LoggerInterface               $logger,
    protected SerializerInterface $serializer,
  ) {
    parent::__construct($logger);
  }

  protected function toEntity(Request $request, string $type) {
    return $this->serializer->deserialize($request->getContent(), $type, 'json');
  }

  protected function json($data, $status = 200, $headers = [], $context = []): JsonResponse {
    return parent::json($data, $status, $headers, [
      AbstractObjectNormalizer::SKIP_NULL_VALUES => true,
      AbstractObjectNormalizer::SKIP_UNINITIALIZED_VALUES => true,
      DateTimeNormalizer::FORMAT_KEY => 'Y-m-d\TH:i:s.v\Z',
    ]);
  }
}
