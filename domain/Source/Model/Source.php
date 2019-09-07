<?php

declare(strict_types=1);

namespace Domain\Source\Model;

use Domain\Source\Enum\Status;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Source extends Model
{
    protected $fillable = [
        'url',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function scopeWhereActive(Builder $builder): Builder
    {
        return $builder->where('status', Status::active()->getValue());
    }
}
