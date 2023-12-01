<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Searchable\Searchable;
use Kyslik\ColumnSortable\Sortable;
use Spatie\Searchable\SearchResult;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements Searchable
{
    use HasApiTokens, HasFactory, Notifiable, Sortable, HasRoles;

    protected $table = 'users';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;

    const USER_TYPE_ADMIN = 1;
    const USER_TYPE_STAFF = 2;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'phone_number',
        'google_token',
        'google_access_id',
        'notification_token',
        'created_by',
        'profile_image',
        'language',
        'status',
        'restaurant_id',
        'address',
        'city',
        'state',
        'country',
        'zip',
        'user_type'
    ];

    public $sortable = [
        'id',
        'first_name',
        'email',
        'created_at',
        'phone_number',
        // 'restaurant.name',
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
        'first_name' => "string",
        'last_name' => "string",
        'email' => "string",
        'password' => "string",
        'phone_number' => "string",
        'google_token' => "string",
        'google_access_id' => "string",
        'notification_token' => "string",
        'created_by' => "integer",
        'profile_image' => "string",
        'language' => "string",
        'status' => "integer",
        'restaurant_id' => "integer",
        'address' => "string",
        'city' => "string",
        'state' => "string",
        'country' => "string",
        'user_type' => "integer"
    ];

    public function restaurant()
    {
        $table = (new Restaurant())->getTable();

        return $this->hasOne(Restaurant::class, 'id', 'restaurant_id')->select(
            "$table.name",
            "$table.instagram_url",
            "$table.script_code",
            "$table.intro_video_url",
            "$table.instagram_token",
            "$table.logo",
            "$table.cover_image",
            "$table.theme",
            "$table.language",
            "$table.id",
            "$table.uuid",
            "$table.menu_title_en",
            "$table.menu_title_ar",
            "$table.created_at",
        );
    }

    public function restaurants()
    {
        $table = (new Restaurant())->getTable();
        return $this->belongsToMany(Restaurant::class, 'restaurant_users', 'user_id', 'restaurant_id')->withPivot("role")->wherePivot('role', RestaurantUser::ROLE_STAFF)->select(
            "$table.name",
            "$table.logo",
            "$table.cover_image",
            "$table.theme",
            "$table.language",
            "$table.id",
            "$table.created_at",
        );
    }



    public function getNameAttribute()
    {
        return ucfirst($this->first_name) . " " . ucfirst($this->last_name);
    }
    public function getLogoNameAttribute()
    {
        return ucfirst(substr($this->first_name, 0, 1));
    }

    public function setProfileImageAttribute($value)
    {
        $this->attributes['profile_image'] = uploadFile($value, 'profile');
    }

    public function getProfileUrlAttribute()
    {
        return getFileUrl($this->attributes['profile_image']);
    }
    public function getSearchResult(): SearchResult
    {
        $title = $this->name;
        $user = auth()->user();
        if ($user->id == $this->id) {
            $url = route('restaurant.profile');
        } else {
            $url = route('restaurant.users.edit', $this->id);
        }

        return new \Spatie\Searchable\SearchResult(
            $this,
            $this->name,
            $url
        );
    }

    public function isRest()
    {
        return $this->hasRole('restaurant');
    }

    public function isAdmin()
    {
        return $this->hasRole('admin');
    }
}
