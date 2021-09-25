<?php

declare(strict_types=1);

namespace App\Tests\Feature\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    /**
     * @dataProvider localelessLandingProvider
     */
    public function testLocalelessLanding(string $acceptLanguageHeader, string $expectedLocale): void
    {
        $client = static::createClient();
        $client->request('GET', '/', [], [], [
            'HTTP_ACCEPT_LANGUAGE' => $acceptLanguageHeader,
        ]);

        $this->assertTrue($client->getResponse()->isRedirect('/' . $expectedLocale));
    }

    public function localelessLandingProvider(): array
    {
        return [
            'none' => [ '', 'en' ],
            'english plain' => [ 'en', 'en' ],
            'english GB' => [ 'en-GB', 'en' ],
            'english with quality' => [ 'en-GB,en;q=0.5', 'en' ],
            'japanese plain' => [ 'ja', 'ja' ],
            'japanese JP' => [ 'ja-JP', 'en' ], // don't have that locale, use fallback
            'japanese with quality' => [ 'ja-JP,ja;q=0.5', 'ja' ],
            'mixed, japanese primary' => [ 'ja-JP,ja;q=0.9, en-GB,en;q=0.5', 'ja'  ],
            'mixed, english primary' => [ 'ja-JP,ja;q=0.5, en-GB,en;q=0.9', 'en'  ],
            'unsupported' => [ 'fr-CH, fr;q=0.9, de;q=0.7', 'en' ],
        ];
    }

    /**
     * @dataProvider localesProvider
     */
    public function testIndexExists(string $locale): void
    {
        $client = static::createClient();
        $client->request('GET', '/' . $locale);

        $this->assertTrue($client->getResponse()->isSuccessful());
    }

    public function localesProvider(): array
    {
        return [
            'English' => [ 'en' ],
            'Japanese' => [ 'ja' ],
        ];
    }
}
