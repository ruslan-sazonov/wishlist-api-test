<?php

namespace App\Generic\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;

class GenericApiException extends HttpException
{
    /** @var array $errors */
    protected $errors = [];

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @param array $errors
     * @return $this
     */
    protected function setErrors(array $errors): self
    {
        $this->errors = $errors;

        return $this;
    }
}