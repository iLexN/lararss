<?php

declare(strict_types=1);

namespace Domain\Services\Rss;

use Zend\Feed\Reader\Feed\FeedInterface;
use Zend\Feed\Reader\Reader;

final class RssReader
{
    public function import(string $url): FeedInterface
    {
        return Reader::import($url);
    }
}
