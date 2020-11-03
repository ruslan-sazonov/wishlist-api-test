<?php

namespace App\Controller\API;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

class ApiController extends AbstractController
{
    /**
     * @param $data
     * @param int $status
     * @param array $headers
     * @param array $context
     * @return JsonResponse
     */
    public function response($data, int $status = Response::HTTP_OK, array $headers = [], array $context = []): JsonResponse
    {
        $context = array_merge([
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                return $object;
            },
            'json_encode_options' => JsonResponse::DEFAULT_ENCODING_OPTIONS
        ], $context);

        $json = $this->container->get('serializer')->serialize($data, 'json', $context);

        return new JsonResponse($json, $status, $headers, true);
    }
}
