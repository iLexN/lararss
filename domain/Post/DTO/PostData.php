<?php

declare(strict_types=1);

namespace Domain\Post\DTO;

use Carbon\Carbon;
use Domain\Source\Model\Source;
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
     * @var int
     */
    private $created;

    /**
     * @var \Domain\Source\Model\Source
     */
    private $source;

    /**
     * @var string
     */
    private $content;

    public function __construct(
        string $title,
        string $url,
        string $description,
        Carbon $created,
        string $content,
        Source $source
    ) {
        $this->title = $title;
        $this->url = $url;
        $this->description = $description;
        $this->created = $created;
        $this->content = $content;
        $this->source = $source;
    }

    public static function createFromArray(array $data): PostData
    {
        return new self(
            $data['title'],
            $data['url'],
            $data['description'],
            $data['created'],
            $data['content'],
            Source::Find($data['source_id'])
        );
    }

    public static function createFromZendReader(EntryInterface $item, Source $source): PostData
    {
        return new self(
            $item->getTitle(),
            $item->getLink(),
            $item->getDescription(),
            Carbon::instance($item->getDateCreated()),
            $item->getContent(),
            $source
        );
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
     * @return \Domain\Source\Model\Source
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

    public function toArray(callable $callback = null): array
    {
        if ($callback === null) {
            return [
                'title' => $this->getTitle(),
                'url' => $this->getUrl(),
                'description' => $this->getDescription(),
                'created' => $this->getCreated(),
                'content' => $this->getContent(),
                'source_id' => $this->getSource()->id,
            ];
        }

        return $callback($this);
    }
}
