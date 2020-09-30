<?php

namespace App\Tests\Command\Wishlist;

use App\Command\Wishlist\AddProductCommand;
use App\Generic\Exception\InvalidArgumentApiException;
use PHPUnit\Framework\TestCase;

class AddProductCommandTest extends TestCase
{
    public function testCommandSuccess()
    {
        $productId = 4;
        $wishlistId = 3;

        $command = new AddProductCommand();
        $command->setProductId($productId);
        $command->setWishlistId($wishlistId);

        $this->assertEquals($productId, $command->getProductId());
        $this->assertEquals($wishlistId, $command->getWishlistId());
    }

    public function testCommandMalformed()
    {
        $productId = 5;
        $wishlistId = 'wrong';

        $command = new AddProductCommand();

        $command->setProductId($productId);
        $this->assertEquals($productId, $command->getProductId());
        $this->expectException(InvalidArgumentApiException::class);
        $command->setWishlistId($wishlistId);
    }

    public function testCommandMissingArgument()
    {
        $productId = null;
        $wishlistId = 5;

        $command = new AddProductCommand();

        $this->expectException(InvalidArgumentApiException::class);
        $command->setProductId($productId);
        $command->setWishlistId($wishlistId);
        $this->assertEquals($wishlistId, $command->getWishlistId());
    }

    public function testCommandNegativeIds()
    {
        $productId = -5;
        $wishlistId = -44;

        $command = new AddProductCommand();

        $this->expectException(InvalidArgumentApiException::class);
        $command->setProductId($productId);
        $command->setWishlistId($wishlistId);
    }
}