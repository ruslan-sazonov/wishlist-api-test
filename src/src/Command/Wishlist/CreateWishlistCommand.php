<?php

namespace App\Command\Wishlist;

use App\Generic\Command\GenericCommand;
use Symfony\Component\Validator\Constraints as Assert;

class CreateWishlistCommand extends GenericCommand
{
    protected $defaultValues = [
        'isActive' => true,
    ];

    /** @var string $name */
    private $name;
    /** @var bool $isActive */
    private $isActive;

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
    public function getIsActive(): bool
    {
        return $this->isActive ?? $this->defaultValues['isActive'];
    }

    /**
     * @param mixed $name
     * @return CreateWishlistCommand
     */
    public function setName($name): self
    {
        $constraints = [
            new Assert\NotBlank(),
            new Assert\Type('string'),
        ];

        $this->assert($constraints, 'name', $name);
        $this->name = $name;

        return $this;
    }

    /**
     * @param mixed $isActive
     * @return CreateWishlistCommand
     */
    public function setIsActive($isActive): self
    {
        $constraints = [
            new Assert\Optional([
                new Assert\Type(['type' => 'boolean']),
            ])
        ];

        $this->assert($constraints, 'isActive', $isActive);
        $this->isActive = $isActive ?? $this->defaultValues['isActive'];

        return $this;
    }
}