<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */


    protected $fillable = [
        'name', 'email', 'password','role_id', 'is_active'
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


        /**
     * Un utilisateur peut créer plusieurs cours.
     */
    public function coursesCreated()
    {
        return $this->hasMany(Course::class, 'created_by');
    }

    /**
     * Un utilisateur peut être inscrit à plusieurs sessions.
     */
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    /**
     * Un utilisateur peut enseigner plusieurs sessions.
     */
    public function modulesTaught()
    {
        return $this->hasMany(Module::class, 'instructor_id');
    }

    /**
     * Un utilisateur a plusieurs notifications.
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * Un utilisateur suit la progression sur plusieurs cours.
     */
    public function progress()
    {
        return $this->hasMany(Progress::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function learner()
    {
        return $this->hasOne(Learner::class);
    }

    public function Instructor()
    {
        return $this->hasOne(Instructor::class);
    }

}
