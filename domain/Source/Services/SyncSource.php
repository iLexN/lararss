<?php

declare(strict_types=1);

namespace Domain\Source\Services;

use Domain\Services\Rss\RssReaderInterface;
use Domain\Source\Model\Source;
use Domain\Source\Services\Error\SyncSourceUrlError;
use Illuminate\Contracts\Validation\Factory;

final class SyncSource
{
    /**
     * @var RssReaderInterface
     */
    private $rssReader;
    /**
     * @var Factory
     */
    private $factory;

    /**
     * @param RssReaderInterface $rssReader
     * @param Factory $factory
     */
    public function __construct(RssReaderInterface $rssReader, Factory $factory)
    {
        $this->rssReader = $rssReader;
        $this->factory = $factory;
    }

    /**
     * @param Source $source
     * @throws SyncSourceUrlError
     */
    public function sync(Source $source): void
    {
        $this->check($source);
        $this->import($source);
    }

    private function import($source): void
    {
        $this->rssReader->import($source->url);
        //do thing with feed
    }

    /**
     * @param Source $source
     * @throws SyncSourceUrlError
     */
    private function check(Source $source): void
    {
        $v = $this->factory->make($source->toArray(), [
            'url' => 'active_url|required',
        ]);
        if ($v->fails()) {
            throw SyncSourceUrlError::createFromSource($source,$v->errors()->first('url'));
        }
    }
}
