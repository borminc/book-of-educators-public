<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class InstructorLevel extends Model
{
    use HasFactory;

    protected $fillable = [
        'level',
        'role',
    ];

    /**
     * The users that belong to the InstructorLevel
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'instructor_level_user', 'instructor_level_id', 'user_id');
    }
}
