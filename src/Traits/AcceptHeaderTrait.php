<?php

declare(strict_types = 1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

namespace Hyperf\Http\Message\Util\Traits;

trait AcceptHeaderTrait
{
    /**
     * Gets a list of header values from header string.
     *
     * @param string $headerValue
     *
     * @return array
     */
    public static function getHeaderValuesFromString(string $headerValue) : array
    {
        $index = 0;
        $parts = HeaderUntils::split($headerValue, ',;=');

        return self::getKeyItems(\array_map(function ($subParts) use (&$index)
        {
            $part                = \array_shift($subParts);
            $attributes          = HeaderUntils::combine($subParts);
            $attributes['index'] = $index++;

            return [$part[0] => $attributes];
        }, $parts));
    }

    /**
     * Sorts items by descending quality.
     *
     * @param array $items
     *
     * @return array
     */
    private static function sortHeaderItems(array $items) : array
    {
        \uasort($items, function ($a, $b)
        {
            $a = \array_values($a)[0];
            $b = \array_values($b)[0];

            $qA = $a['q'] ?? 1.0;
            $qB = $b['q'] ?? 1.0;

            if ($qA === $qB) {
                return $a['index'] > $b['index'] ? 1 : -1;
            }

            return $qA > $qB ? -1 : 1;
        });

        return $items;
    }

    /**
     * Get only the key from the item.
     *
     * @param array $items
     *
     * @return array
     */
    private static function getKeyItems(array $items) : array
    {
        $keyItems = [];

        foreach (self::sortHeaderItems($items) as $item) {
            $keyItems[] = \key($item);
        }

        return $keyItems;
    }
}
