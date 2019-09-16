<?php

namespace Domain\Post\Model;

use Domain\Source\Model\Source;
use Domain\Support\Enum\Status;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Post extends Model
{
    protected $fillable = [
        'title',
        'url',
        'description',
        'created',
        'content',
        'source_id',
        'status',
        'pick',
    ];

    protected $casts = [
        'created' => 'datetime',
        'status' => 'bool',
        'pick' => 'bool',
    ];

    /*
    |--------------------------------------------------------------------------
    | Scope Section: for query
    |--------------------------------------------------------------------------
    */
    public function scopeSource(Builder $builder, int $sourceId): Builder
    {
        return $builder->where('source_id', $sourceId);
    }

    public function scopeActive(Builder $builder): Builder
    {
        return $builder->where('status', Status::active()->getValue());
    }

    public function scopePick(Builder $builder): Builder
    {
        return $builder->where('pick', Status::active()->getValue());
    }

    public function scopeSortCreatedAsc(Builder $builder): Builder
    {
        return $builder->orderBy('created');
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function source(): BelongsTo
    {
        return $this->belongsTo(Source::class);
    }
}
