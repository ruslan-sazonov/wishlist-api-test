<?php

namespace App\Generic\Interfaces\Hydrator;

interface ConstructableFromArrayInterface
{
    /**
     * @param array $array
     */
    public static function fromArray(array $array);
}