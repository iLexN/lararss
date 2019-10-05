<?php

declare(strict_types=1);

namespace Domain\Source\DTO;

use Domain\Support\Enum\Status;
use Illuminate\Support\Facades\Validator;
use TheCodingMachine\GraphQLite\Annotations\Factory;

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

    private function __construct(string $url, Status $status)
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
        if (is_callable($callback)) {
            return $callback($this);
        }
        return [
            'url' => $this->getUrl(),
            'status' => $this->getStatus()->getValue(),
        ];
    }

    public static function createFromArray(array $data): self
    {
        $v = validator::make($data, [
            'status' => 'required|boolean',
            'url' => 'active_url|required',
        ]);

        if ($v->fails()) {
            throw new \InvalidArgumentException($v->errors()->toJson());
        }

        return new self(
            $data['url'],
            new Status($data['status'])
        );
    }

    /**
     * The Factory annotation will create automatically a LocationInput input type in GraphQL.
     *
     * @Factory()
     *
     * @param string $url
     * @param int $status
     * @return SourceData
     */
    public static function createByAttr(string $url, bool $status): self
    {
        return self::createFromArray(
            [
                'url' => $url,
                'status' => $status,
            ]
        );
    }
}
