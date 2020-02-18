<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

namespace Hyperf\Http\Message\Util;

use Hyperf\Http\Message\Util\Traits\AcceptHeaderTrait;
use Psr\Http\Message\MessageInterface;

class InteractsWithAcceptLanguage
{
    use AcceptHeaderTrait;

    /**
     * Gets a list of languages acceptable by the client browser.
     *
     * @return array Languages ordered in the user browser preferences
     */
    public static function getLanguages(MessageInterface $message): array
    {
        $languagesFromString = self::getHeaderValuesFromString($message->getHeaderLine('Accept-Language'));
        $languages = [];

        foreach ($languagesFromString as $lang) {
            if (\mb_strpos($lang, '-') !== false) {
                $codes = \explode('-', $lang);

                if ($codes[0] === 'i') {
                    // Language not listed in ISO 639 that are not variants
                    // of any listed language, which can be registered with the
                    // i-prefix, such as i-cherokee
                    if (\count($codes) > 1) {
                        $lang = $codes[1];
                    }
                } else {
                    foreach ($codes as $i => $iValue) {
                        if ($i === 0) {
                            $lang = \mb_strtolower($codes[0]);
                        } else {
                            $lang .= '_' . \mb_strtoupper($codes[$i]);
                        }
                    }
                }
            }

            $languages[] = $lang;
        }

        return $languages;
    }
}
