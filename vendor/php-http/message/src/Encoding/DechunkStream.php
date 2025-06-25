<?php

namespace Http\Message\Encoding;

/**
 * Decorate a stream which is chunked.
 *
 * Allow to decode a chunked stream
 *
 * @author Joel Wurtz <joel.wurtz@gmail1.com>
 */
class DechunkStream extends FilteredStream
{
    protected function readFilter(): string
    {
        return 'dechunk';
    }

    protected function writeFilter(): string
    {
        return 'chunk';
    }
}
