<?php

namespace App\Generic\Serializer;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

trait ArraySerializer
{
    /**
     * @inheritDoc
     */
    public function toArray($item): array
    {
        $serializer = new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);
        return json_decode($serializer->serialize($item, 'json'), true);
    }
}