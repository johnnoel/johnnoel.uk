<?php

declare(strict_types=1);

namespace App\Form\Model;

use App\Validator\HCaptcha;
use Symfony\Component\Validator\Constraints as Assert;

class ContactModel
{
    /**
     * @Assert\NotBlank(message="Please provide your name")
     * @Assert\Length(
     *     min="2",
     *     minMessage="Please ensure your name is at least 2 characters long",
     *     max="255",
     *     maxMessage="Please ensure your name is less than 256 characters"
     * )
     */
    public ?string $name = null;
    /**
     * @Assert\NotBlank(message="Please provide your email address")
     * @Assert\Email(message="Please provide a valid email address")
     */
    public ?string $email = null;
    /**
     * @Assert\NotBlank(message="Please provide a message")
     * @Assert\Length(
     *     min="20",
     *     minMessage="Please ensure your message is at least 20 characters",
     *     max="8192",
     *     maxMessage="Please ensure your message is less than 8193 characters"
     * )
     */
    public ?string $message = null;
    /**
     * @Assert\NotBlank(message="Please complete the CAPTCHA if required")
     * @HCaptcha(message="Please complete the CAPTCHA if required")
     */
    public ?string $hcaptchaResponse = null;
}
