<?php

namespace App\Generic\EventListener;

use App\Generic\Exception\GenericApiException;
use App\Generic\Serializer\ApiExceptionNormalizer;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelEvents;

class ApiExceptionSubscriber implements EventSubscriberInterface
{
    protected $normalizer;


    public function __construct(ApiExceptionNormalizer $normalizer)
    {
        $this->normalizer = $normalizer;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException',
        ];
    }

    /**
     * @param ExceptionEvent $event
     */
    public function onKernelException(ExceptionEvent $event)
    {
        $throwable = $event->getThrowable();
        $data = [];

        if ($throwable instanceof GenericApiException) {
            $data = $this->normalizer->normalize($throwable);
        } elseif ($throwable instanceof NotFoundHttpException) {
            $data = [
                'message' => $throwable->getMessage()
            ];
        } else {
            return;
        }

        $response = new JsonResponse(
            $data,
            $throwable->getStatusCode()
        );

        $response->headers->set('Content-Type', 'application/problem+json');
        $event->setResponse($response);
    }
}