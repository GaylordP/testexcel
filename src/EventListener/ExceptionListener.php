<?php

namespace App\EventListener;

use App\Exception\HttpJsonException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class ExceptionListener
{
    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();

        if ($exception instanceof HttpJsonException) {
            $event->setResponse(
                new JsonResponse(
                    $exception->getMessage(),
                    $exception->getCode()
                )
            );
        }
    }
}
