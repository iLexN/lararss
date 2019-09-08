<?php

declare(strict_types=1);


namespace Domain\Services\Rss;

use Zend\Feed\Reader\Feed\FeedInterface;

interface RssReaderInterface
{
    public function import(string $url): FeedInterface;
}
