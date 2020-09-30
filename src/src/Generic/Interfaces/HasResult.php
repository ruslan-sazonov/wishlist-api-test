<?php

namespace App\Generic\Interfaces;

interface HasResult
{
    /**
     * @param Arrayable $result
     */
    public function setResult(Arrayable $result);

    /**
     * @return array
     */
    public function getResult(): array;
}