<?php

namespace App\Tests\Command\Wishlist;

use App\Command\Wishlist\AddProductCommand;
use App\Command\Wishlist\CreateWishlistCommand;
use App\Generic\Exception\InvalidArgumentApiException;
use PHPUnit\Framework\TestCase;

class CreateWishlistCommandTest extends TestCase
{
    public function testCommandSuccess()
    {
        $name = 'Really good name';
        $isActive = true;
        $command = new CreateWishlistCommand();

        $command->setName($name);
        $command->setIsActive($isActive);
        $this->assertEquals($name, $command->getName());
        $this->assertEquals($isActive, $command->getIsActive());
        $this->assertThat($command->getIsActive(), $this->isType('boolean'));
    }

    public function testCommandWrongNameType()
    {
        $name = 44444;

        $command = new CreateWishlistCommand();
        $this->expectException(InvalidArgumentApiException::class);
        $command->setName($name);
    }

    public function testCommandNameIsEmpty()
    {
        $name = null;

        $command = new CreateWishlistCommand();
        $this->expectException(InvalidArgumentApiException::class);
        $command->setName($name);
    }

    public function testCommandWrongIsActiveType()
    {
        $isActive = 'whoop';

        $command = new CreateWishlistCommand();
        $this->expectException(InvalidArgumentApiException::class);
        $command->setIsActive($isActive);
    }

    public function testCommandIsActiveOptional()
    {
        $name = 'I am a test wishlist';
        $isActiveDefaultValue = true;
        $command = new CreateWishlistCommand();

        $command->setName($name);
        $this->assertEquals($name, $command->getName());
        $this->assertThat($command->getIsActive(), $this->isType('boolean'));
        $this->assertEquals($isActiveDefaultValue, $command->getIsActive());
    }
}