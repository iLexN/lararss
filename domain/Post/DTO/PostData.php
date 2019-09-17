<?php

declare(strict_types=1);

namespace Domain\Post\DTO;

use Carbon\Carbon;
use Domain\Post\Enum\Pick;
use Domain\Source\DbModel\Source;
use Domain\Support\Enum\Status;
use Zend\Feed\Reader\Entry\EntryInterface;

final class PostData
{
    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $url;

    /**
     * @var string
     */
    private $description;

    /**
     * @var Carbon
     */
    private $created;

    /**
     * @var Source
     */
    private $source;

    /**
     * @var string
     */
    private $content;

    /**
     * @var Status
     */
    private $status;

    /**
     * @var Pick
     */
    private $pick;

    public function __construct(
        string $title,
        string $url,
        string $description,
        Carbon $created,
        string $content,
        Source $source,
        Status $status,
        Pick $pick
    ) {
        $this->title = $title;
        $this->url = $url;
        $this->description = $description;
        $this->created = $created;
        $this->content = $content;
        $this->source = $source;
        $this->status = $status;
        $this->pick = $pick;
    }

    public static function createFromArray(array $data): PostData
    {
        return new self(
            $data['title'],
            $data['url'],
            $data['description'],
            $data['created'],
            $data['content'],
            Source::Find($data['source_id']),
            Status::active(),
            Pick::unpick()
        );
    }

    public static function createFromZendReader(
        EntryInterface $item,
        Source $source
    ): PostData {
        return new self(
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

    public function toArray(callable $callback = null): array
    {
        if (is_callable($callback)) {
            return $callback($this);
        }

        return [
            'title' => $this->getTitle(),
            'url' => $this->getUrl(),
            'description' => $this->getDescription(),
            'created' => $this->getCreated(),
            'content' => $this->getContent(),
            'source_id' => $this->getSource()->id,
            'status' => $this->getStatus()->getValue(),
            'pick' => $this->getPick()->getValue(),
        ];
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return Carbon
     */
    public function getCreated(): Carbon
    {
        return $this->created;
    }

    /**
     * @return Source
     */
    public function getSource(): Source
    {
        return $this->source;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @return Status
     */
    public function getStatus(): Status
    {
        return $this->status;
    }

    /**
     * @return Pick
     */
    public function getPick(): Pick
    {
        return $this->pick;
    }
}
