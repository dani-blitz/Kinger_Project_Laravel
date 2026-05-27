<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    // Константы ролей
    const ROLE_USER = 'user';
    const ROLE_MODERATOR = 'moderator';
    const ROLE_ADMIN = 'admin';
    const ROLE_SUPER_ADMIN = 'super_admin';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'steam_id',
        'steam_nickname',
        'steam_avatar',
        'steam_last_login',
        'is_admin',
        'role',
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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'steam_last_login' => 'datetime',
    ];

    // Связь с дополнительными правами
    public function extraPermissions()
    {
        return $this->hasMany(UserPermission::class);
    }

    // Проверка базовой роли
    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === self::ROLE_SUPER_ADMIN;
    }

    public function isAdmin(): bool
    {
        return in_array($this->role, [self::ROLE_ADMIN, self::ROLE_SUPER_ADMIN]);
    }

    public function canPublishNewsDirectly(): bool
    {
        return $this->canDo('news.publish_direct') || $this->isAdmin();
    }

    public function isModerator(): bool
    {
        return in_array($this->role, [self::ROLE_MODERATOR, self::ROLE_ADMIN, self::ROLE_SUPER_ADMIN]);
    }

    // Проверка конкретного права (с учётом роли и дополнительных прав)
    public function canDo(string $permission): bool
    {
        // Супер-админ может всё
        if ($this->isSuperAdmin()) {
            return true;
        }

        // Проверяем дополнительное право (если оно переопределяет стандартное)
        $extraPermission = $this->extraPermissions()->where('permission', $permission)->first();
        if ($extraPermission) {
            return $extraPermission->value;
        }

        // Проверяем по роли
        return $this->getDefaultPermissionByRole($permission);
    }

    // Стандартные права по ролям
    protected function getDefaultPermissionByRole(string $permission): bool
    {
        $permissions = [
            self::ROLE_USER => [
                'reports.create' => true,
                'reports.view_own' => true,
                'reports.comment_own' => true,
                'news.suggest' => true,
                'news.view' => true,
                'servers.view' => true,
            ],
            self::ROLE_MODERATOR => [
                'reports.create' => true,
                'reports.view_all' => true,
                'reports.view_own' => true,
                'reports.comment_all' => true,
                'reports.change_status' => true,
                'reports.comment_own' => true,
                'news.suggest' => true,
                'news.view' => true,
                'servers.view' => true,
            ],
            self::ROLE_ADMIN => [
                'reports.create' => true,
                'reports.view_all' => true,
                'reports.view_own' => true,
                'reports.comment_all' => true,
                'reports.change_status' => true,
                'reports.delete' => true,
                'news.suggest' => true,
                'news.view' => true,
                'news.create' => true,
                'news.publish_direct' => true,
                'news.moderate' => true,
                'news.delete' => true,
                'users.view' => true,
                'users.edit' => true,
                'servers.view' => true,
            ],
        ];

        return $permissions[$this->role][$permission] ?? false;
    }
}
