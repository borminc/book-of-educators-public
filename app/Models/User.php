<?php

namespace App\Models;

use App\Models\Role;
use App\Models\School;
use App\Models\Contact;
use App\Models\Subject;
use App\Models\WorkExperience;
use App\Models\InstructorLevel;
use Illuminate\Http\UploadedFile;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Jetstream\HasProfilePhoto;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Nicolaslopezj\Searchable\SearchableTrait;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use CloudinaryLabs\CloudinaryLaravel\MediaAlly;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto {
        updateProfilePhoto as protected jetUpdateProfilePhoto;
        deleteProfilePhoto as protected jetDeleteProfilePhoto;
        getProfilePhotoUrlAttribute as protected jetGetProfilePhotoUrlAttribute;
    }
    use Notifiable;
    use TwoFactorAuthenticatable;
    use HasRoles;
    use SearchableTrait;
    use MediaAlly;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'title',
        'bio',
    ];

    protected $with = [
        'roles',
        'medially',
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
        'name',
    ];

    /**
     * Name attribute
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    public function name(): Attribute
    {
        return Attribute::make(
            get: fn () => "$this->first_name $this->last_name",
        );
    }

    /**
     * Get the contact associated with the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function contact(): HasOne
    {
        return $this->hasOne(Contact::class);
    }

    /**
     * The instructorLevels that belong to the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function instructorLevels(): BelongsToMany
    {
        return $this->belongsToMany(InstructorLevel::class, 'instructor_level_user', 'user_id', 'instructor_level_id');
    }

    /**
     * The subjects that belong to the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class, 'subject_user', 'user_id', 'subject_id');
    }

    /**
     * Get all of the workExperiences for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function workExperiences(): HasMany
    {
        return $this->hasMany(WorkExperience::class);
    }

    /**
     * Get the school associated with the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function school(): HasOne
    {
        return $this->hasOne(School::class);
    }

    /**
     * Search query when value exists
     *
     * @param mixed $query
     * @param string|null $searchKey
     * @return mixed
     */
    public function scopeSearchWhenExists($query, $searchKey)
    {
        $like = config('database.default') === 'pgsql' ? 'ILIKE' : 'LIKE';
        $args =  ["%$searchKey%"];

        return $query->when($searchKey, fn ($query) => $query
            ->whereRaw("first_name $like ?", $args)
            ->orWhereRaw("last_name $like ?", $args)
            ->orWhereRaw("CONCAT(first_name, ' ', last_name) $like ?", $args)
            ->orWhereRaw("title $like ?", $args)
            ->orWhereRaw("bio $like ?", $args)
            ->orWhereHas('contact', fn ($q) => $q->whereRaw("phone $like ?", $args))
            ->orWhereHas('contact', fn ($q) => $q->whereRaw("email $like ?", $args))
            ->orWhere(function ($Q) use ($args, $like) {
                // school related
                return $Q->role(Role::SCHOOL)
                    ->where(fn ($q) => $q->whereHas('school', fn ($q) => $q->whereRaw("name $like ?", $args)));
            })
            ->orWhere(function ($Q) use ($args, $like) {
                // instructor-related
                return $Q->role(Role::INSTRUCTOR)
                    ->where(fn ($q) => $q->orWhereHas('instructorLevels', fn ($q) => $q->whereRaw("role $like ?", $args))
                        ->orWhereHas('instructorLevels', fn ($q) => $q->whereRaw("level $like ?", $args))
                        ->orWhereHas('subjects', fn ($q) => $q->whereRaw("name $like ?", $args))
                        ->orWhereHas('subjects', fn ($q) => $q->whereRaw("keywords $like ?", $args))
                        ->orWhereHas('workExperiences', fn ($q) => $q->whereRaw("company $like ?", $args))
                        ->orWhereHas('workExperiences', fn ($q) => $q->whereRaw("title $like ?", $args))
                        ->orWhereHas('workExperiences', fn ($q) => $q->whereRaw("location $like ?", $args))
                        ->orWhereHas('workExperiences', fn ($q) => $q->whereRaw("description $like ?", $args)));
            }));
    }

    /**
     * Update the user's profile photo. (Overriding Jetstream)
     *
     * @param  \Illuminate\Http\UploadedFile  $photo
     * @return void
     */
    public function updateProfilePhoto(UploadedFile $photo)
    {
        if (config('app.env', 'production') === 'local') {
            return $this->jetUpdateProfilePhoto($photo);
        }

        $this->updateMedia($photo);
        $this->forceFill([
            'profile_photo_path' => 'true',
        ])->save(); // fake path
    }

    /**
     * Delete the user's profile photo. (Overriding Jetstream)
     *
     * @return void
     */
    public function deleteProfilePhoto()
    {
        if (config('app.env', 'production') === 'local') {
            return $this->jetDeleteProfilePhoto();
        }

        $this->detachMedia();
        $this->forceFill([
            'profile_photo_path' => null,
        ])->save();
    }

    /**
     * Get the URL to the user's profile photo. (Overriding Jetstream)
     *
     * @return string
     */
    public function getProfilePhotoUrlAttribute()
    {
        if (config('app.env', 'production') === 'local') {
            return $this->jetGetProfilePhotoUrlAttribute();
        }

        return $this->fetchFirstMedia()?->getSecurePath() ?? $this->defaultProfilePhotoUrl();
    }
}
