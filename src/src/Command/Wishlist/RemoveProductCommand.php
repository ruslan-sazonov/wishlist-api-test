<?php

namespace App\Command\Wishlist;

use App\Generic\Command\GenericCommand;
use Symfony\Component\Validator\Constraints as Assert;

class RemoveProductCommand extends GenericCommand
{
    /** @var int $wishlistId */
    private $wishlistId;
    /** @var int $productId */
    private $productId;

    /**
     * @return int
     */
    public function getWishlistId()
    {
        return $this->wishlistId;
    }

    /**
     * @return int
     */
    public function getProductId()
    {
        return $this->productId;
    }

    /**
     * @param mixed $wishlistId
     * @return RemoveProductCommand
     */
    public function setWishlistId($wishlistId): self
    {
        $constraints = [
            new Assert\NotBlank(),
            new Assert\Positive()
        ];

        $this->assert($constraints, 'wishlistId', $wishlistId);
        $this->wishlistId = $wishlistId;
        return $this;
    }

    /**
     * @param mixed $productId
     * @return RemoveProductCommand
     */
    public function setProductId($productId): self
    {
        $constraints = [
            new Assert\NotBlank(),
            new Assert\Positive()
        ];

        $this->assert($constraints, 'productId', $productId);
        $this->productId = $productId;
        return $this;
    }
}