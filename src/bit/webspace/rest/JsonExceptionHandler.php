<?php

namespace App\bit\webspace\rest;

use App\bit\webspace\data\EntityNotFoundException;
use App\bit\webspace\data\JsonSerializable;
use App\bit\webspace\data\ViolationException;
use Doctrine\ORM\OptimisticLockException;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\Exception\NotNormalizableValueException;
use TypeError;

class JsonExceptionHandler implements EventSubscriberInterface {

  public function __construct(protected LoggerInterface $logger) {
  }

  public static function getSubscribedEvents(): array {
    return [KernelEvents::EXCEPTION => 'onKernelException'];
  }

  public function onKernelException(ExceptionEvent $exceptionEvent): void {
    $request = $exceptionEvent->getRequest();
    if (!str_starts_with($request->getPathInfo(), '/api/')) {
      return;
    }
    $ex = $exceptionEvent->getThrowable();
    if ($ex instanceof EntityNotFoundException) {
      $exceptionEvent->setResponse(new JsonResponse($ex->asJson(), 404));
      return;
    }
    if ($ex instanceof JsonSerializable) {
      $exceptionEvent->setResponse(new JsonResponse($ex->asJson(), 400));
      return;
    }
    $data = ['error' => [
      'message' => $ex->getMessage(),
      'code' => $ex->getCode(),
      'file' => $ex->getFile(),
      'line' => $ex->getLine(),
    ]];
    if ($ex instanceof NotEncodableValueException || $ex instanceof NotNormalizableValueException) {
      $vex = new ViolationException($ex->getMessage(), $request->getContent(), "[{$request->getMethod()}] {$request->getPathInfo()}");
      $exceptionEvent->setResponse(new JsonResponse($vex->asJSON(), 400));
      return;
    }
    if ($ex instanceof OptimisticLockException) {
      $vex = new ViolationException($ex->getMessage(), $request->getContent(), "[{$request->getMethod()}] {$request->getPathInfo()}");
      $exceptionEvent->setResponse(new JsonResponse($vex->asJSON(), 409));
      return;
    }
    if ($ex instanceof TypeError) {
      $exceptionEvent->setResponse(new JsonResponse(['error' => [
        'message' => $ex->getMessage(),
        'code' => $ex->getCode(),
        'file' => $ex->getFile(),
        'line' => $ex->getLine(),
      ]], 400));
      return;
    }
    $this->logger->error('unexpected error:',
      ['path' => $request->getPathInfo(), 'type' => get_class($ex), 'error' => $data]);
    $exceptionEvent->setResponse(new JsonResponse($data, 400));
  }
}
