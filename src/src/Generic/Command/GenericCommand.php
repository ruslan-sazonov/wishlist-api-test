<?php

namespace App\Generic\Command;

use App\Generic\Interfaces\Arrayable;
use App\Generic\Interfaces\HasResult;
use App\Generic\Exception\InvalidArgumentApiException;
use App\Generic\Serializer\ArraySerializer;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;

class GenericCommand implements HasResult, Arrayable
{
    use ArraySerializer;

    /** @var array $defaultValues */
    protected $defaultValues;
    /** @var Arrayable $result */
    protected $result;
    /** @var ValidatorInterface $validator */
    private $validator;

    /**
     * @param Constraint[] $constraints
     * @param string $field
     * @param $value
     */
    public function assert(array $constraints, $field, $value)
    {
        $constraintCollection = new Assert\Collection([
            $field => $constraints,
        ]);
        $valueArray = [
            $field => $value
        ];

        $violations = $this->getValidator()->validate($valueArray, $constraintCollection);

        if ($violations->count()) {
            throw new InvalidArgumentApiException($violations, 422, 'Invalid Input Data');
        }
    }

    /**
     * @inheritDoc
     */
    public function setResult(Arrayable $result)
    {
        $this->result = $result;
    }

    /**
     * @inheritDoc
     */
    public function getResult(): array
    {
        return $this->toArray($this->result);
    }

    /**
     * @return ValidatorInterface
     */
    private function getValidator()
    {
        if (is_null($this->validator)) {
            $this->validator = Validation::createValidator();
        }

        return $this->validator;
    }
}