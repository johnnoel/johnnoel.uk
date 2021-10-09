<?php

declare(strict_types=1);

namespace App\Extension\Twig;

use RelativeTime\RelativeTime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
    /**
     * @return array<TwigFilter>
     */
    public function getFilters(): array
    {
        return [
            new TwigFilter('time_diff', [ $this, 'timediff' ]),
        ];
    }

    public function timeDiff(string|int $timeFrom, string|int $timeTo = 'now'): string
    {
        $relativeTime = new RelativeTime();

        return $relativeTime->convert((string)$timeTo, (string)$timeFrom);
    }
}
