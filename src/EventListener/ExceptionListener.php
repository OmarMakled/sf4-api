<?php

namespace App\EventListener;

use App\Exception\NotEmptyException;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ExceptionListener
{
  /**
   * Hook on kernel exception.
   *
   * @param GetResponseForExceptionEvent $event
   * @return JsonResponse
   */
  public function onKernelException(GetResponseForExceptionEvent $event)
  {
    $exception = $event->getException();

    $event->setResponse(new JsonResponse([
      'error' => $exception->getMessage(),
    ], $exception->getStatusCode()));
  }
}
