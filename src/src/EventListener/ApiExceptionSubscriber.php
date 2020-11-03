<?php

namespace App\EventListener;

use App\Serializer\Normalizer\ApiExceptionNormalizer;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\KernelEvents;

class ApiExceptionSubscriber implements EventSubscriberInterface
{
    /** @var ApiExceptionNormalizer $normalizer */
    protected $normalizer;

    public function __construct(ApiExceptionNormalizer $normalizer)
    {
        $this->normalizer = $normalizer;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
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

        if ($throwable instanceof HttpExceptionInterface) {
            $data = $this->normalizer->normalize($throwable);
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
