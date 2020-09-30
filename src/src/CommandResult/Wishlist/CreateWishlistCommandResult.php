<?php

namespace App\CommandResult\Wishlist;

use App\Generic\CommandResult\GenericCommandResult;

class CreateWishlistCommandResult extends GenericCommandResult
{
    /** @var int $id */
    protected $id;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return CreateWishlistCommandResult
     */
    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }


}