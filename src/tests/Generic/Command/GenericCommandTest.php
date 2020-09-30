<?php

namespace App\Tests\Generic\Command;

use App\Generic\Command\GenericCommand;
use App\Generic\Exception\InvalidArgumentApiException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Constraints as Assert;

class GenericCommandTest extends TestCase
{

    public function testCommandAssertions()
    {
        $constraints = [
            new Assert\Type('integer')
        ];
        $value = 'this is wrong type';
        $field = 'testProp';
        $command = new GenericCommand();

        $this->expectException(InvalidArgumentApiException::class);
        $command->assert($constraints, $field, $value);
    }
}