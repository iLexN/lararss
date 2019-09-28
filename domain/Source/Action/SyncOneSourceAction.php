<?php

declare(strict_types=1);

namespace Domain\Source\Action;

use Domain\Post\Action\SyncPost;
use Domain\Post\DTO\NewPostData;
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
     * @var SyncPost
     */
    private $SyncPost;
    /**
     * @var updateSyncDateNowAction
     */
    private $syncDateNowAction;

    /**
     * @param RssReaderInterface $rssReader
     * @param Factory $factory
     * @param SyncPost $syncPost
     * @param updateSyncDateNowAction $syncDateNowAction
     */
    public function __construct(
        RssReaderInterface $rssReader,
        Factory $factory,
        SyncPost $syncPost,
        updateSyncDateNowAction $syncDateNowAction
    ) {
        $this->rssReader = $rssReader;
        $this->factory = $factory;
        $this->SyncPost = $syncPost;
        $this->syncDateNowAction = $syncDateNowAction;
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
            $postData = NewPostData::createFromZendReaderBySourceModel($item, $source);
            $this->SyncPost->onQueue()->execute($postData);
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
