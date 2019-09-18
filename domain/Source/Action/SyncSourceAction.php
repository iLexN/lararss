<?php

declare(strict_types=1);

namespace Domain\Source\Action;

use Domain\Post\Action\CreatePostAction;
use Domain\Post\DTO\PostData;
use Domain\Services\Rss\RssReaderInterface;
use Domain\Source\Action\Error\SyncSourceUrlError;
use Domain\Source\DbModel\Source;
use Illuminate\Contracts\Validation\Factory;
use Spatie\QueueableAction\QueueableAction;

final class SyncSourceAction
{
    use QueueableAction;

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
    public function execute(Source $source): void
    {
        $this->check($source);
        $this->import($source);
    }

    private function import(Source $source): void
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
