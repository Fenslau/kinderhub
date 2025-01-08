<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\UserRoleEnum;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class User extends Authenticatable implements FilamentUser, MustVerifyEmail, HasAvatar
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'email_verified_at',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        if ($panel->getId() === 'admin') {
            return $this->isActive();
        }
        return true;
    }

    public function getFilamentAvatarUrl(): ?string
    {
        if (!empty($this->profile?->image)) {
            if (Str::startsWith($this->profile?->image, 'user-images')) {
                return Storage::url($this->profile?->image);
            } else return $this->profile?->image;
        } else return null;
    }

    public function profile(): HasOne
    {
        return $this->hasOne(UserProfile::class);
    }


    public function isActive(): bool
    {
        return $this->profile?->is_active === 1 && !empty($this->email_verified_at) && !$this->trashed();
    }

    public function isAdmin(): bool
    {
        return $this->profile?->role === UserRoleEnum::ADMIN && $this->isActive();
    }

    public function isModer(): bool
    {
        return $this->profile?->role === UserRoleEnum::MODERATOR && $this->isActive();
    }
}
