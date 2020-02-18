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

use Psr\Http\Message\MessageInterface;

class InteractsWithAuthorization
{
    /**
     * Get the authorization from http header.
     *
     * @param \Psr\Http\Message\MessageInterface $message
     *
     * @return null|array
     */
    public static function getAuthorization(MessageInterface $message): ?array
    {
        $header = $message->getHeaderLine('Authorization');
        $matches = [];

        if (! \preg_match('/^\s*(\S+)\s+(\S+)/', $header, $matches)) {
            return null;
        }

        return [$matches[1], $matches[2]];
    }
}
