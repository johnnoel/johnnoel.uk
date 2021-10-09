<?php

declare(strict_types=1);

namespace App\Exception;

use Exception;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class InvalidProjectException extends Exception
{
    public function __construct(private ConstraintViolationListInterface $errors)
    {
        parent::__construct($this->buildMessage(), 400);
    }

    private function buildMessage(): string
    {
        $messages = array_map(function (ConstraintViolationInterface $error): string {
            return $error->getPropertyPath() . ': ' . $error->getMessage();
        }, iterator_to_array($this->errors));

        return implode(PHP_EOL, $messages);
    }
}
