<?php

namespace App\Generic\Exception;

use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class InvalidArgumentApiException extends GenericApiException
{

    public function __construct(
        ConstraintViolationListInterface $violations,
        int $statusCode,
        string $message = null,
        \Throwable $previous = null,
        array $headers = [],
        ?int $code = 0
    ) {
        parent::__construct($statusCode, $message, $previous, $headers, $code);
        $this->setErrors($this->getViolationsAsArray($violations));
    }

    /**
     * @param ConstraintViolationListInterface $violations
     * @return array
     */
    protected function getViolationsAsArray(ConstraintViolationListInterface $violations): array
    {
        $errors = [];

        /** @var ConstraintViolation $violation */
        foreach ($violations as $violation) {
            $errors[] = [
                'propertyPath' => $violation->getPropertyPath(),
                'message' => $violation->getMessage(),
            ];
        }

        return $errors;
    }

}