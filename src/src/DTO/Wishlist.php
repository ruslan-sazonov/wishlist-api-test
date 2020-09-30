<?php

namespace App\DTO;

use App\Generic\DTO\GenericDto;

class Wishlist extends GenericDto
{
    /** @var int $id */
    private $id;
    /** @var int $userId */
    private $userId;
    /** @var string $name */
    private $name;
    /** @var bool $isActive */
    private $isActive;

    /**
     * @param int $id
     * @param int $userId
     * @param string $name
     * @param bool $isActive
     */
    public function __construct(int $id, int $userId, string $name, bool $isActive)
    {
        $this->id = $id;
        $this->userId = $userId;
        $this->name = $name;
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
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->isActive;
    }
}
