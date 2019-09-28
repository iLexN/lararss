<?php

declare(strict_types=1);


namespace Domain\Post\DTO;

use Carbon\Carbon;
use Domain\Post\Enum\Pick;
use Domain\Source\DbModel\Source;
use Domain\Source\Model\SourceBusinessModel;
use Domain\Source\Model\SourceBusinessModelFactory;
use Domain\Source\Repository\SourceRepository;
use Domain\Support\Enum\Status;
use Zend\Feed\Reader\Entry\EntryInterface;

final class NewPostDataFactory
{
    /**
     * @var SourceRepository
     */
    private $sourceRepository;

    public function __construct(SourceRepository $sourceRepository)
    {
        $this->sourceRepository = $sourceRepository;
    }

    public function createFromArray(array $data): NewPostData
    {
        return new NewPostData(
            $data['title'] ?? '',
            $data['url'] ?? '',
            $data['description'] ?? '',
            $data['created'] ?? Carbon::now(),
            $data['content'] ?? '',
            $this->sourceRepository->getOne($data['source_id']),
            Status::active(),
            Pick::unpick()
        );
    }

    public function createFromZendReader(
        EntryInterface $item,
        Source $source
    ): NewPostData {
        return new NewPostData(
            $item->getTitle(),
            $item->getLink(),
            $item->getDescription(),
            Carbon::make($item->getDateCreated()),
            $item->getContent(),
            (new SourceBusinessModelFactory())->createOne($source),
            Status::active(),
            Pick::unpick()
        );
    }

    public function createFromZendReaderBySourceModel(
        EntryInterface $item,
        SourceBusinessModel $source
    ): NewPostData {
        return new NewPostData(
            $item->getTitle(),
            $item->getLink(),
            $item->getDescription(),
            Carbon::make($item->getDateCreated()),
            $item->getContent(),
            $source,
            Status::active(),
            Pick::unpick()
        );
    }
}
