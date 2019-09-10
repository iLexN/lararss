<?php

declare(strict_types=1);

namespace Domain\Source\Services;

use Domain\Post\Action\CreatePostAction;
use Domain\Post\DTO\PostData;
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
     * @var CreatePostAction
     */
    private $createPostAction;

    /**
     * @param RssReaderInterface $rssReader
     * @param Factory $factory
     * @param CreatePostAction $createPostAction
     */
    public function __construct(RssReaderInterface $rssReader, Factory $factory, CreatePostAction $createPostAction)
    {
        $this->rssReader = $rssReader;
        $this->factory = $factory;
        $this->createPostAction = $createPostAction;
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
        $feed = $this->rssReader->import($source->url);
        //do thing with feed
        foreach ($feed as $item) {
            $postData = PostData::createFromZendReader($item, $source);
            $this->createPostAction->onQueue()->execute($postData);
        }
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
            throw SyncSourceUrlError::createFromSource($source, $v->errors()->first('url'));
        }
    }
}
