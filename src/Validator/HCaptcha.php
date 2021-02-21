<?php

declare(strict_types=1);

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class HCaptcha extends Constraint
{
    public string $message = 'Please complete the hCaptcha';
}
