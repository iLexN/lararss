<?php

declare(strict_types=1);

namespace Domain\Source\DbModel;

use Domain\Post\DbModel\Post;
use Domain\Source\Domain\SubDomain\SourceIsWithInUpdateRange;
use Domain\Support\Enum\Status;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

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

    public function scopeNeedSync(Builder $builder): Builder
    {
        $moreThanHour = Carbon::now()->subHours(SourceIsWithInUpdateRange::UPDATED_RANGE_HOUR);
        return $builder->where('updated_at', '<', $moreThanHour);
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
