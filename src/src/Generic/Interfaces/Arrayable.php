<?php

namespace App\Generic\Interfaces;

interface Arrayable
{
    /**
     * @param $item
     * @return array
     */
    public function toArray($item): array;
}