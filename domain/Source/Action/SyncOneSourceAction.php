<?php

declare(strict_types=1);

namespace Domain\Source\Action;

use Domain\Post\Action\SyncPostFromFeedItemAction;
use Domain\Services\Rss\RssReaderInterface;
use Domain\Source\Action\Error\SyncSourceUrlError;
use Domain\Source\Model\SourceBusinessModel;
use Illuminate\Contracts\Validation\Factory;
use Spatie\QueueableAction\QueueableAction;

final class SyncOneSourceAction
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
     * @var updateSyncDateNowAction
     */
    private $syncDateNowAction;

    /**
     * @var SyncPostFromFeedItemAction
     */
    private $syncPostFromFeedItem;

    /**
     * @param RssReaderInterface $rssReader
     * @param Factory $factory
     * @param updateSyncDateNowAction $syncDateNowAction
     * @param SyncPostFromFeedItemAction $syncPostFromFeedItem
     */
    public function __construct(
        RssReaderInterface $rssReader,
        Factory $factory,
        updateSyncDateNowAction $syncDateNowAction,
        SyncPostFromFeedItemAction $syncPostFromFeedItem
    ) {
        $this->rssReader = $rssReader;
        $this->factory = $factory;
        $this->syncDateNowAction = $syncDateNowAction;
        $this->syncPostFromFeedItem = $syncPostFromFeedItem;
    }

    /**
     * @param SourceBusinessModel $source
     * @throws SyncSourceUrlError
     */
    public function execute(SourceBusinessModel $source): void
    {
        $this->check($source);
        $this->import($source);
        $this->syncDateNowAction->execute($source->getOperationModel());
    }

    private function import(SourceBusinessModel $source): void
    {
        $feed = $this->rssReader->import($source->getUrl());
        //do thing with feed
        foreach ($feed as $item) {
            $this->syncPostFromFeedItem->execute($item, $source);
        }
    }

    /**
     * @param SourceBusinessModel $source
     * @throws SyncSourceUrlError
     */
    private function check(SourceBusinessModel $source): void
    {
        $v = $this->factory->make($source->toArray(), [
            'url' => 'active_url|required',
        ]);
        if ($v->fails()) {
            throw SyncSourceUrlError::createFromSourceModel($source, $v->errors()->first('url'));
        }
    }
}
