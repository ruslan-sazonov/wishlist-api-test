<?php

namespace App\DTO;

use App\Generic\DTO\GenericDto;

class Product extends GenericDto
{
    /** @var int $id */
    private $id;
    /** @var string $name */
    private $name;
    /** @var string $sku */
    private $sku;
    /** @var int $price */
    private $price;
    /** @var bool $isActive */
    private $isActive;

    /**
     * @param int $id
     * @param string $name
     * @param string $sku
     * @param int $price
     * @param bool $isActive
     */
    public function __construct(int $id, string $name, string $sku, int $price, bool $isActive)
    {
        $this->id = $id;
        $this->name = $name;
        $this->sku = $sku;
        $this->price = $price;
        $this->isActive = $isActive;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getSku(): string
    {
        return $this->sku;
    }

    /**
     * @return int
     */
    public function getPrice(): int
    {
        return $this->price;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->isActive;
    }
}
