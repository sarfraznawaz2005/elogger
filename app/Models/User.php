<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'basecamp_org_id',
        'basecamp_org',
        'basecamp_api_key',
        'basecamp_api_user_id',
        'working_hours_count',
        'holidays_count',
        'calculations',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];

    public function isAdmin(): bool
    {
        return $this->is_admin === 1;
    }

    /**
     * Get's user's projects in which he has time entries
     *
     * @return HasMany
     */
    public function projects(): HasMany
    {
        return $this->hasMany(Project::class)->where('hours', '>', 0);
    }

    /**
     * Gets all projects of user
     *
     * @return HasMany
     */
    public function projectsAll(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    /**
     * Gets all todos of user.
     *
     * @return HasMany
     */
    public function todos(): HasMany
    {
        return $this->hasMany(Todo::class);
    }

    /**
     * Gets all posted todos of user.
     *
     * @return HasMany
     */
    public function postedTodos(): HasMany
    {
        return $this->hasMany(Todo::class)
            ->where('status', 'posted');
    }

    /**
     * Gets all pending todos of user.
     *
     * @return HasMany
     */
    public function pendingTodos(): HasMany
    {
        return $this->hasMany(Todo::class)
            ->where('status', 'pending');
    }

    /** @noinspection ALL */
    public function pendingTodosToday(): HasMany
    {
        return $this->hasMany(Todo::class)
            ->where('dated', date('Y-m-d'))
            ->where('status', 'pending');
    }

    public function pendingTodosHoursToday($userId = 0): float
    {
        $hours = 0;

        if ($userId) {
            $todos = $this->pendingTodosToday()->where('user_id', $userId)->get();
        } else {
            $todos = $this->pendingTodosToday;
        }

        foreach ($todos as $todoToday) {
            $diff = (float)getBCHoursDiff($todoToday->dated, $todoToday->time_start, $todoToday->time_end);

            $hours += $diff;
        }

        return $hours;
    }

    public function pendingTodosHoursMonth($userId = 0): float
    {
        $hours = 0;

        if ($userId) {
            $todos = $this->pendingTodos()
                ->where('user_id', $userId)
                ->whereMonth('dated', date('m'))
                ->get();
        } else {
            $todos = $this->pendingTodos()->whereMonth('dated', date('m'))->get();
        }

        foreach ($todos as $todoToday) {
            $diff = (float)getBCHoursDiff($todoToday->dated, $todoToday->time_start, $todoToday->time_end);

            $hours += $diff;
        }

        return $hours;
    }

    public function pendingTodosHours($userId = 0): float
    {
        $hours = 0;

        if ($userId) {
            $todos = $this->pendingTodos()->where('user_id', $userId)->get();
        } else {
            $todos = $this->pendingTodos;
        }

        foreach ($todos as $todoToday) {
            $diff = (float)getBCHoursDiff($todoToday->dated, $todoToday->time_start, $todoToday->time_end);

            $hours += $diff;
        }

        return $hours;
    }

    /** @noinspection ALL */
    public function postedTodosHours($userId = 0): float
    {
        $hours = 0;

        if ($userId) {
            $todos = $this->postedTodos()->where('user_id', $userId)->get();
        } else {
            $todos = $this->postedTodos;
        }

        foreach ($todos as $todoToday) {
            $diff = (float)getBCHoursDiff($todoToday->dated, $todoToday->time_start, $todoToday->time_end);

            $hours += $diff;
        }

        return $hours;
    }
}
