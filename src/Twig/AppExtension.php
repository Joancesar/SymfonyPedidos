<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('urlSanitize', [$this, 'urlSanitize']),
        ];
    }

    public function urlSanitize($value)
    {
        $value = iconv('utf-8', 'us-ascii//TRANSLIT', $value);
        $value = preg_replace('/[\W]+/', '-', $value);
        $value = preg_replace('/-+/', '-', $value);
        $value = trim(strtolower($value));
        return $value;
    }
}
