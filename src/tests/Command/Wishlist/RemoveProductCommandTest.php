<?php

namespace App\Tests\Command\Wishlist;

use App\Command\Wishlist\RemoveProductCommand;
use App\Generic\Exception\InvalidArgumentApiException;
use PHPUnit\Framework\TestCase;

class RemoveProductCommandTest extends TestCase
{
    public function testCommandSuccess()
    {
        $productId = 32;
        $wishlistId = 7;
        $command = new RemoveProductCommand();

        $command->setProductId($productId);
        $command->setWishlistId($wishlistId);
        $this->assertEquals($productId, $command->getProductId());
        $this->assertEquals($wishlistId, $command->getWishlistId());
    }

    public function testCommandMalformed()
    {
        $productId = 'badId';
        $wishlistId = 'imlosthere';
        $command = new RemoveProductCommand();

        $this->expectException(InvalidArgumentApiException::class);
        $command->setProductId($productId);
        $command->setWishlistId($wishlistId);
    }

    public function testCommandMissingArgument()
    {
        $productId = '';
        $wishlistId = null;
        $command = new RemoveProductCommand();

        $this->expectException(InvalidArgumentApiException::class);
        $command->setProductId($productId);
        $command->setWishlistId($wishlistId);
    }

    public function testCommandNegativeIds()
    {
        $productId = 0;
        $wishlistId = -64;
        $command = new RemoveProductCommand();
        
        $this->expectException(InvalidArgumentApiException::class);
        $command->setProductId($productId);
        $command->setWishlistId($wishlistId);
    }
}