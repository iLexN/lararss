<?php

declare(strict_types=1);

namespace Domain\Source\DTO;

use Domain\Support\Enum\Brand;
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
    /**
     * @var Brand
     */
    private $brand;

    private function __construct(string $url, Status $status, Brand $brand)
    {
        $this->url = $url;
        $this->status = $status;
        $this->brand = $brand;
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

    /**
     * @return Brand
     */
    public function getBrand(): Brand
    {
        return $this->brand;
    }

    public function toArray(callable $callback = null): array
    {
        if (is_callable($callback)) {
            return $callback($this);
        }
        return [
            'url' => $this->getUrl(),
            'status' => $this->getStatus()->getValue(),
            'brand' => $this->getBrand()->getValue(),
        ];
    }

    public static function createFromArray(array $data): self
    {
        $v = validator::make($data, [
            'status' => 'required|boolean',
            'url' => 'active_url|required',
            'brand' => 'required',
        ]);

        if ($v->fails()) {
            throw new \InvalidArgumentException($v->errors()->toJson());
        }

        return new self(
            $data['url'],
            new Status($data['status']),
            new Brand($data['brand'])
        );
    }

    /**
     * The Factory annotation will create automatically a LocationInput input type in GraphQL.
     *
     * @Factory()
     *
     * @param string $url
     * @param bool $status
     * @param string $brand
     * @return SourceData
     */
    public static function createByAttr(string $url, bool $status, string $brand): self
    {
        return self::createFromArray(
            [
                'url' => $url,
                'status' => $status,
                'brand' => $brand,
            ]
        );
    }
}
