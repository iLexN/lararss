<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App{
/**
 * App\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

namespace Domain\Post\Model{
/**
 * Domain\Post\Model\Post
 *
 * @property int $id
 * @property string $title
 * @property string $url
 * @property string $description
 * @property \Illuminate\Support\Carbon $created
 * @property string $content
 * @property int $source_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Domain\Post\Model\Post newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Domain\Post\Model\Post newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Domain\Post\Model\Post query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Domain\Post\Model\Post whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Domain\Post\Model\Post whereCreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Domain\Post\Model\Post whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Domain\Post\Model\Post whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Domain\Post\Model\Post whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Domain\Post\Model\Post whereSourceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Domain\Post\Model\Post whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Domain\Post\Model\Post whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Domain\Post\Model\Post whereUrl($value)
 */
	class Post extends \Eloquent {}
}

namespace Domain\Source\Model{
/**
 * Domain\Source\Model\Source
 *
 * @property int $id
 * @property string $url
 * @property bool $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Domain\Post\Model\Post[] $posts
 * @property-read int|null $posts_count
 * @method static \Illuminate\Database\Eloquent\Builder|\Domain\Source\Model\Source active()
 * @method static \Illuminate\Database\Eloquent\Builder|\Domain\Source\Model\Source newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Domain\Source\Model\Source newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Domain\Source\Model\Source query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Domain\Source\Model\Source whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Domain\Source\Model\Source whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Domain\Source\Model\Source whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Domain\Source\Model\Source whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Domain\Source\Model\Source whereUrl($value)
 */
	class Source extends \Eloquent {}
}

