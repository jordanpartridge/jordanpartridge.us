<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */

namespace App\Models{
    /**
     * @property int $id
     * @property string $content
     * @property int $post_id
     * @property int $user_id
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property-read \App\Models\Post|null $post
     * @property-read \App\Models\User|null $user
     *
     * @method static \Database\Factories\CommentFactory factory($count = null, $state = [])
     * @method static \Illuminate\Database\Eloquent\Builder|Comment newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|Comment newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|Comment query()
     * @method static \Illuminate\Database\Eloquent\Builder|Comment whereContent($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Comment whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Comment whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Comment wherePostId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Comment whereUpdatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Comment whereUserId($value)
     */
    class Comment extends \Eloquent
    {
    }
}

namespace App\Models{
    /**
     * @property int $id
     * @property string $name
     * @property string $deck_slug
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property string|null $deleted_at
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $players
     * @property-read int|null $players_count
     *
     * @method static \Database\Factories\GameFactory factory($count = null, $state = [])
     * @method static \Illuminate\Database\Eloquent\Builder|Game newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|Game newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|Game query()
     * @method static \Illuminate\Database\Eloquent\Builder|Game whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Game whereDeckSlug($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Game whereDeletedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Game whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Game whereName($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Game whereUpdatedAt($value)
     */
    class Game extends \Eloquent
    {
    }
}

namespace App\Models{
    /**
     * @property int $id
     * @property int $user_id
     * @property string $title
     * @property string|null $body
     * @property string|null $image
     * @property string $slug
     * @property string|null $excerpt
     * @property string $type
     * @property string $status
     * @property int $active
     * @property int $featured
     * @property string|null $meta_title
     * @property string|null $meta_description
     * @property string|null $meta_schema
     * @property string|null $meta_data
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property-read \App\Models\User|null $user
     *
     * @method static \Illuminate\Database\Eloquent\Builder|Post excludeFeatured()
     * @method static \Database\Factories\PostFactory factory($count = null, $state = [])
     * @method static \Illuminate\Database\Eloquent\Builder|Post list()
     * @method static \Illuminate\Database\Eloquent\Builder|Post newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|Post newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|Post published()
     * @method static \Illuminate\Database\Eloquent\Builder|Post query()
     * @method static \Illuminate\Database\Eloquent\Builder|Post typePost()
     * @method static \Illuminate\Database\Eloquent\Builder|Post whereActive($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Post whereBody($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Post whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Post whereExcerpt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Post whereFeatured($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Post whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Post whereImage($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Post whereMetaData($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Post whereMetaDescription($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Post whereMetaSchema($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Post whereMetaTitle($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Post whereSlug($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Post whereStatus($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Post whereTitle($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Post whereType($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Post whereUpdatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Post whereUserId($value)
     */
    class Post extends \Eloquent
    {
    }
}

namespace App\Models{
    /**
     * @property int $id
     * @property \Carbon\Carbon $date
     * @property string $moving_time
     * @property float|null $elapsed_time
     * @property string $name
     * @property string|null $polyline
     * @property string|null $map_url
     * @property string|false $elevation
     * @property string $external_id
     * @property float $max_speed
     * @property int|null $calories
     * @property float $average_speed
     * @property float $distance
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property-read string $ride_diff
     *
     * @method static \Database\Factories\RideFactory factory($count = null, $state = [])
     * @method static \Illuminate\Database\Eloquent\Builder|Ride newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|Ride newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|Ride query()
     * @method static \Illuminate\Database\Eloquent\Builder|Ride whereAverageSpeed($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Ride whereCalories($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Ride whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Ride whereDate($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Ride whereDistance($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Ride whereElapsedTime($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Ride whereElevation($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Ride whereExternalId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Ride whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Ride whereMapUrl($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Ride whereMaxSpeed($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Ride whereMovingTime($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Ride whereName($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Ride wherePolyline($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Ride whereUpdatedAt($value)
     */
    class Ride extends \Eloquent
    {
    }
}

namespace App\Models{
    /**
     * @property int $id
     * @property int $user_id
     * @property string $access_token
     * @property \Illuminate\Support\Carbon $expires_at
     * @property string $refresh_token
     * @property int $athlete_id
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property-read bool $expired
     * @property-read \App\Models\User $user
     *
     * @method static \Database\Factories\StravaTokenFactory factory($count = null, $state = [])
     * @method static \Illuminate\Database\Eloquent\Builder|StravaToken newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|StravaToken newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|StravaToken query()
     * @method static \Illuminate\Database\Eloquent\Builder|StravaToken whereAccessToken($value)
     * @method static \Illuminate\Database\Eloquent\Builder|StravaToken whereAthleteId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|StravaToken whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|StravaToken whereExpiresAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|StravaToken whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|StravaToken whereRefreshToken($value)
     * @method static \Illuminate\Database\Eloquent\Builder|StravaToken whereUpdatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|StravaToken whereUserId($value)
     */
    class StravaToken extends \Eloquent
    {
    }
}

namespace App\Models{
    /**
     * @method static updateOrCreate(string[] $array, array $array1)
     *
     * @property int $id
     * @property string $name
     * @property string $email
     * @property string|null $avatar
     * @property \Illuminate\Support\Carbon|null $email_verified_at
     * @property mixed $password
     * @property string|null $remember_token
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
     * @property-read int|null $notifications_count
     * @property-read \App\Models\StravaToken|null $stravaToken
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
     * @property-read int|null $tokens_count
     *
     * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
     * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|User query()
     * @method static \Illuminate\Database\Eloquent\Builder|User whereAvatar($value)
     * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
     * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
     * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
     * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
     * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
     */
    class User extends \Eloquent implements \Filament\Models\Contracts\FilamentUser, \Filament\Models\Contracts\HasAvatar
    {
    }
}
