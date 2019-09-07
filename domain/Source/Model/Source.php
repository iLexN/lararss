<?php
declare(strict_types=1);

namespace Domain\Source\Model;

use Illuminate\Database\Eloquent\Model;

class Source extends Model
{
    protected $fillable = [
        'url',
        'status',
    ];
}
