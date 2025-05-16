<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Filament\Panel;
use JordanPartridge\StravaClient\Concerns\HasStravaTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use JordanPartridge\StravaClient\Contracts\HasStravaToken;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;

/**
 * @method static updateOrCreate(string[] $array, array $array1)
 */
class User extends Authenticatable implements FilamentUser, HasAvatar, HasStravaToken
{
    use HasApiTokens;
    use HasStravaTokens;
    use HasFactory;
    use HasRoles;
    use Notifiable;
    use LogsActivity;
    use SendsPasswordResetEmails;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'avatar',
        'bio',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the clients managed by the user.
     */
    public function clients(): HasMany
    {
        return $this->hasMany(Client::class);
    }

    /**
     * Get the user's Gmail token.
     */
    public function gmailToken()
    {
        return $this->hasOne(GmailToken::class);
    }

    /**
     * Check if the user has a valid Gmail token.
     */
    public function hasValidGmailToken(): bool
    {
        $token = $this->gmailToken;

        return $token && !$token->isExpired();
    }

    /**
     * Get the Gmail client for the user.
     */
    public function getGmailClient()
    {
        $token = $this->gmailToken;

        if (!$token) {
            return null;
        }

        return app(\PartridgeRocks\GmailClient\GmailClient::class)->authenticate(
            $token->access_token,
            $token->refresh_token,
            $token->expires_at->toDateTime()
        );
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }

    public function getFilamentAvatarUrl(): ?string
    {
        return $this->avatar ? asset($this->avatar) : null;
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->useLogName('system');
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }
}
