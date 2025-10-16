<?php
namespace App\Helper;

use App;
use Illuminate\Http\Request;

class LocaleHelper
{
    public static function getLocalizedURL($locale = null, $url = null)
    {
        if ($locale === null) {
            $locale = App::getLocale();
        }

        $url = $url ?: Request::url();
        $parsed = parse_url($url);
        $segments = explode('/', trim($parsed['path'], '/'));

        // Remplacer ou ajouter le préfixe de langue
        if (in_array($segments[0], config('app.available_locales'))) {
            $segments[0] = $locale;
        } else {
            array_unshift($segments, $locale);
        }

        return $parsed['scheme'] . '://' . $parsed['host'] . '/' . implode('/', $segments);
    }
}
