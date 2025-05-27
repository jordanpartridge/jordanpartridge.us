<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Filament\Panel;
use JordanPartridge\StravaClient\Concerns\HasStravaTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
     * Get all Gmail accounts for the user.
     */
    public function gmailAccounts()
    {
        return $this->hasMany(GmailToken::class);
    }

    /**
     * Get the primary Gmail account for the user.
     */
    public function primaryGmailAccount()
    {
        return $this->hasOne(GmailToken::class)->where('is_primary', true);
    }

    /**
     * Get the user's Gmail token (backwards compatibility - returns primary account).
     */
    public function gmailToken()
    {
        return $this->primaryGmailAccount();
    }

    /**
     * Check if the user has any valid Gmail tokens.
     */
    public function hasValidGmailToken(): bool
    {
        return $this->gmailAccounts()->active()->exists();
    }

    /**
     * Check if the user has a valid primary Gmail token.
     */
    public function hasValidPrimaryGmailToken(): bool
    {
        $token = $this->primaryGmailAccount;
        return $token && !$token->isExpired();
    }

    /**
     * Get the Gmail client for the user's primary account.
     *
     * Retrieves an authenticated Gmail client instance using the user's primary account tokens.
     * Uses the global namespace resolution for the GmailClient class to prevent
     * namespace resolution issues.
     *
     * @return \PartridgeRocks\GmailClient\GmailClient|null The authenticated Gmail client or null if no token exists
     */
    public function getGmailClient()
    {
        $token = $this->primaryGmailAccount;

        if (!$token || $token->isExpired()) {
            return null;
        }

        return app(\PartridgeRocks\GmailClient\GmailClient::class)->authenticate(
            $token->access_token,
            $token->refresh_token,
            $token->expires_at->toDateTime()
        );
    }

    /**
     * Get Gmail client for a specific account.
     *
     * @param string $gmailEmail The Gmail email address
     * @return \PartridgeRocks\GmailClient\GmailClient|null
     */
    public function getGmailClientForAccount(string $gmailEmail)
    {
        $token = $this->gmailAccounts()->where('gmail_email', $gmailEmail)->first();

        if (!$token || $token->isExpired()) {
            return null;
        }

        return app(\PartridgeRocks\GmailClient\GmailClient::class)->authenticate(
            $token->access_token,
            $token->refresh_token,
            $token->expires_at->toDateTime()
        );
    }

    /**
     * Get count of connected Gmail accounts.
     */
    public function getGmailAccountsCountAttribute(): int
    {
        return $this->gmailAccounts()->active()->count();
    }

    /**
     * Get list of connected Gmail emails.
     */
    public function getConnectedGmailEmailsAttribute(): array
    {
        return $this->gmailAccounts()->active()->pluck('gmail_email')->filter()->toArray();
    }

    /**
     * Determine if the user can access the given Filament panel.
     *
     * @param Panel $panel The Filament panel to check access for
     * @return bool Always returns true as all users can access panels
     */
    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }

    /**
     * Get the URL for the user's Filament avatar.
     *
     * @return string|null The URL to the user's avatar or null if no avatar exists
     */
    public function getFilamentAvatarUrl(): ?string
    {
        return $this->avatar ? asset($this->avatar) : null;
    }

    /**
     * Get the activity log options for this model.
     *
     * Configures which attributes are logged and how the log entries are named.
     *
     * @return LogOptions The configured activity log options
     */
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
