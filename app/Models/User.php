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

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
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
    ];

    public function exams()
    {
        return $this->hasMany(Exam::class);
    }

    public function essays()
    {
        return $this->hasMany(Essay::class);
    }

    public const ROLE_STUDENT   = 'student';
    public const ROLE_PROFESSOR = 'professor';
    public const ROLE_ADMIN     = 'admin';

    public const ROLES = [
        self::ROLE_STUDENT,
        self::ROLE_PROFESSOR,
        self::ROLE_ADMIN,
    ];

    public const ROLE_LABELS = [
        self::ROLE_STUDENT   => 'Estudante',
        self::ROLE_PROFESSOR => 'Professor',
        self::ROLE_ADMIN     => 'Admin',
    ];

    public function isStudent(): bool
    {
        return $this->role === self::ROLE_STUDENT;
    }

    public function isProfessor(): bool
    {
        return $this->role === self::ROLE_PROFESSOR;
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    /** Professors and admins can access the teacher panel */
    public function canTeach(): bool
    {
        return in_array($this->role, [self::ROLE_PROFESSOR, self::ROLE_ADMIN], true);
    }

    public function roleLabel(): string
    {
        return self::ROLE_LABELS[$this->role] ?? ucfirst($this->role);
    }
}
