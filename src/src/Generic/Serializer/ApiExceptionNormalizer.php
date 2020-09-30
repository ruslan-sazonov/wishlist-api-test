<?php

namespace App\Generic\Serializer;

use App\Generic\Exception\GenericApiException;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ApiExceptionNormalizer implements NormalizerInterface
{
    /**
     * @param GenericApiException $object
     * @param string|null $format
     * @param array $context
     * @return array|\ArrayObject|bool|float|int|string|void|null
     */
    public function normalize($object, string $format = null, array $context = [])
    {
        return [
            'message' => $object->getMessage(),
            'errors' => $object->getErrors(),
        ];
    }

    public function supportsNormalization($data, string $format = null)
    {
        return $data instanceof GenericApiException;
    }

}