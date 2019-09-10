<?php

declare(strict_types=1);

namespace Domain\Source\Model;

use Domain\Post\Model\Post;
use Domain\Source\Enum\Status;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Source extends Model
{
    protected $fillable = [
        'url',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | Scope Section: for query
    |--------------------------------------------------------------------------
    */
    public function scopeActive(Builder $builder): Builder
    {
        return $builder->where('status', Status::active()->getValue());
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }
}
