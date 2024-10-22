<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
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
    public function scopeName($query, $name)
    {
        return $query->where('name', 'LIKE', '%' . $name . '%');
    }
    public function scopeEmail($query, $email)
    {
        return $query->where('email', 'LIKE', '%' . $email . '%');
    }
    public static function generateSlug()
    {
        $slug = 'U' . now()->year . now()->month . now()->day;
        $users = User::where('slug', 'like', '%' . $slug . '%')->count();
        if ($users > 0) {
            $slug.= $users + 1;
        } else {
            $slug.= '1';
        }
        return $slug;
    }
    public function thoughts(): HasMany
    {
        return $this->hasMany(Thought::class);
    }
    public function replies(): HasMany
    {
        return $this->hasMany(Reply::class);
    }
}
