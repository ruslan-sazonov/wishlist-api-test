<?php

namespace App\Generic\CommandResult;

use App\Generic\Interfaces\Arrayable;
use App\Generic\Serializer\ArraySerializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class GenericCommandResult implements Arrayable
{
    use ArraySerializer;
}