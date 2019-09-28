<?php

declare(strict_types=1);

namespace Domain\Post\Action;

use Domain\Post\DTO\NewPostDataFactory;
use Domain\Source\Model\SourceBusinessModel;
use Zend\Feed\Reader\Entry\EntryInterface;

final class SyncPostFromFeedItemAction
{
    /**
     * @var SyncPostAction
     */
    private $syncPost;
    /**
     * @var NewPostDataFactory
     */
    private $postDataFactory;

    public function __construct(SyncPostAction $syncPost, NewPostDataFactory $postDataFactory)
    {
        $this->syncPost = $syncPost;
        $this->postDataFactory = $postDataFactory;
    }

    public function execute(EntryInterface $item, SourceBusinessModel $source): void
    {
        $postData = $this->postDataFactory->createFromZendReaderBySourceModel($item, $source);
        $this->syncPost->onQueue()->execute($postData);
    }
}
