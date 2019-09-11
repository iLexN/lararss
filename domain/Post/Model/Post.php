<?php

namespace Domain\Post\Model;

use Illuminate\Database\Eloquent\Model;

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
}
