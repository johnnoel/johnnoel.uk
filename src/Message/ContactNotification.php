<?php

declare(strict_types=1);

namespace App\Message;

use App\Form\Model\ContactModel;

class ContactNotification
{
    private string $name;
    private string $email;
    private string $message;

    public static function createFromModel(ContactModel $model): self
    {
        return new self(
            (string)$model->name,
            (string)$model->email,
            (string)$model->message
        );
    }

    public function __construct(string $name, string $email, string $message)
    {
        $this->name = $name;
        $this->email = $email;
        $this->message = $message;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getMessage(): string
    {
        return $this->message;
    }
}
