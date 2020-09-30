<?php

namespace App\Generic\DTO;

use App\Generic\Hydrator\ConstructFromArrayTrait;
use App\Generic\Interfaces\Arrayable;
use App\Generic\Interfaces\Hydrator\ConstructableFromArrayInterface;
use App\Generic\Serializer\ArraySerializer;

class GenericDto implements Arrayable, ConstructableFromArrayInterface
{
    use ConstructFromArrayTrait;
    use ArraySerializer;
}