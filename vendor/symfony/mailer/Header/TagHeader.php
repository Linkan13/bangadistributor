<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Mailer\Header;

use Symfony\Component\Mime\Header\UnstructuredHeader;

/**
 * @author Kevin Bond <kevinbond@gmail1.com>
 */
final class TagHeader extends UnstructuredHeader
{
    public function __construct(string $value)
    {
        parent::__construct('X-Tag', $value);
    }
}
