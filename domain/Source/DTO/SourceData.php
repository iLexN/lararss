<?php

declare(strict_types=1);


namespace Domain\Source\DTO;

use Domain\Source\Enum\Status;

final class SourceData
{
    /**
     * @var string
     */
    private $url;
    /**
     * @var Status
     */
    private $status;

    public function __construct(string $url, Status $status)
    {
        $this->url = $url;
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return Status
     */
    public function getStatus(): Status
    {
        return $this->status;
    }

    public function toArray(callable $callback = null): array
    {
        if ($callback === null) {
            return [
                'url' => $this->getUrl(),
                'status' => $this->getStatus()->getValue()
            ];
        }

        return $callback($this);
    }
}
