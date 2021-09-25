<?php

declare(strict_types=1);

namespace App\Validator;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\MessageFormatter;
use GuzzleHttp\Middleware;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class HCaptchaValidator extends ConstraintValidator implements LoggerAwareInterface
{
    private string $secretKey;
    private string $siteKey;
    /**
     * Shortcut the validator when testing - true = field is valid, false = field invalid
     */
    private ?bool $override = null;
    private LoggerInterface $logger;

    public function __construct(string $secretKey, string $siteKey)
    {
        $this->secretKey = $secretKey;
        $this->siteKey = $siteKey;
        $this->logger = new NullLogger();
    }

    public function setLogger(LoggerInterface $logger): void
    {
        $this->logger = $logger;
    }

    /**
     * @inheritDoc
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!($constraint instanceof HCaptcha)) {
            throw new UnexpectedTypeException($constraint, HCaptcha::class);
        }

        // testing override
        if ($this->override !== null) {
            if (!$this->override) {
                $this->context->buildViolation($constraint->message)
                    ->addViolation()
                ;
            }

            return;
        }

        $hCaptchaResponse = $value;

        if (!is_string($hCaptchaResponse) || $hCaptchaResponse === '') {
            $this->context->buildViolation($constraint->message)
                ->addViolation()
            ;

            return;
        }

        $stack = HandlerStack::create();
        $stack->push(Middleware::log($this->logger, new MessageFormatter()));

        $http = new Client([
            'handler' => $stack,
            'read_timeout' => 10,
            'connect_timeout' => 10,
            'timeout' => 10,
        ]);

        $response = null;

        try {
            $response = $http->post('https://hcaptcha.com/siteverify', [
                'form_params' => [
                    'secret' => $this->secretKey,
                    'response' => $hCaptchaResponse,
                    'sitekey' => $this->siteKey,
                ],
            ]);
        } catch (GuzzleException $e) {
            $this->logger->error('Got error from hCaptcha: ' . $e->getMessage());

            return;
        }

        $json = json_decode((string)$response->getBody());

        if ($json === null) {
            $this->logger->error('Could not decode JSON from hCaptcha');

            return;
        }

        if ($json->success) {
            return;
        }

        $this->context->buildViolation($constraint->message)
            ->addViolation()
        ;

        if (property_exists($json, 'error-codes') && is_array($json->{'error-codes'})) {
            $this->logger->debug('hCaptcha API said: ' . implode(', ', $json->{'error-codes'}));
        }
    }

    public function setOverride(bool $override): void
    {
        $this->override = $override;
    }
}
