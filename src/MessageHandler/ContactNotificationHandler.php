<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Message\ContactNotification;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Mime\Email;

class ContactNotificationHandler implements MessageHandlerInterface
{
    private MailerInterface $mailer;
    private string $contactAddress;

    public function __construct(MailerInterface $mailer, string $contactAddress)
    {
        $this->mailer = $mailer;
        $this->contactAddress = $contactAddress;
    }

    public function __invoke(ContactNotification $notification): void
    {
        $email = (new Email())
            ->to($this->contactAddress)
            ->subject('Contact from johnnoel.uk')
            ->replyTo($notification->getEmail())
            ->text($notification->getMessage())
        ;

        $this->mailer->send($email);
    }
}
