<?php

namespace App\Models;

use App\Models\User;
use Spatie\Searchable\Searchable;
use Kyslik\ColumnSortable\Sortable;
use Spatie\Searchable\SearchResult;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Ramsey\Uuid\Uuid;

class Restaurant extends Model implements Searchable {

    use HasFactory, Sortable;

    protected $table = 'restaurants';

    const RESTAURANT_TYPE = [
        'Cafe'                     => 'Cafe',
        'Hotel'                    => 'Hotel',
        'Food Truck'               => 'Food Truck',
        'Quick Service Restaurant' => 'Quick Service Restaurant',
        'Pub/Bar'                  => 'Pub/Bar',
    ];

    const LANGUAGES = ["English" => "English", "Hindi" => "Hindi"];

    protected $fillable = [
        'user_id',
        'name',
        'type',
        'logo',
        'cover_image',
        'contact_email',
        'address',
        'phone_number',
        'city',
        'state',
        'country',
        'currency',
        'language',
        'zip',
        'qr_details',
        'theme',
        'dark_logo',
        'instagram_url',
        'uuid',
        'menu_title_en',
        'menu_title_ar',
        'intro_video_url',
        'script_code',
    ];

    protected $casts = [
        'name'          => "string",
        'type'          => "string",
        'logo'          => "string",
        'dark_logo'     => "string",
        'cover_image'   => "string",
        'phone_number'  => "string",
        'address'       => "string",
        'city'          => "string",
        'state'         => "string",
        'country'       => "string",
        'currency'      => "string",
        'language'      => "array",
        'qr_details'    => "json",
        'contact_email' => "string",
        'theme'         => "string",
        'instagram_url' => "string",
    ];

    public $sortable = [
        'id',
        'name',
        'type',
        'phone_number',
        'contact_email',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function (Model $model) {
            $model->setAttribute('uuid', Uuid::uuid4());
            $model->setAttribute('menu_title_en', 'MENU');
            $model->setAttribute('menu_title_ar', 'القائمة');
        });
    }

    public function users()
    {
        $table = (new User())->getTable();

        return $this->belongsToMany(User::class, 'restaurant_users', 'restaurant_id', 'user_id')->select(
            "$table.first_name",
            "$table.last_name",
            "$table.phone_number",
            "$table.email",
            "$table.profile_image",
            "$table.password",
            "$table.id",
            "$table.created_at",

        );
    }

    public function adminUser()
    {
//        $resUser =  RestaurantUser::query()->where('restaurant_id', $this->id)->first();
//        return User::query()->where('id', $resUser->user_id)->first();

        return $this->users()->first();
    }

    public function created_user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function food_categories()
    {
        $table = (new FoodCategory)->getTable();

        return $this->hasMany(FoodCategory::class, 'restaurant_id', 'id')->orderBy('sort_order', 'ASC')->select(
            "$table.id",
            "$table.restaurant_id",
            "$table.category_name",
            "$table.category_image",
            "$table.lang_category_name",
            "$table.sort_order",
            "$table.created_at",
        );
    }

    public function foods()
    {
        return $this->hasMany(Food::class, 'restaurant_id', 'id');
    }

    public static function restaurant_type_dropdown()
    {
        return ['' => __('system.fields.select_restaurant_type')] + Self::RESTAURANT_TYPE;
    }

    public function getLogoNameAttribute()
    {
        return ucfirst(substr($this->name, 0, 1));
    }

    public function getLanguageStringAttribute()
    {
        return implode(', ', $this->language ?? []);
    }

    public function getLogoUrlAttribute()
    {
        return getFileUrl($this->attributes['logo']);
    }

    public function getDarkLogoUrlAttribute()
    {
        return getFileUrl($this->attributes['dark_logo']);
    }

    public function setLogoAttribute($value)
    {
        if($value != null)
        {
            $this->attributes['logo'] = uploadFile($value, 'logo');
        }
    }

    public function setDarkLogoAttribute($value)
    {
        if($value != null)
        {
            $this->attributes['dark_logo'] = uploadFile($value, 'dark_logo');
        }
    }

    public function setPhoneNumberAttribute($value)
    {
        $this->attributes['phone_number'] = str_replace(' ', '', $value);
    }


    public function setQrDetailsAttribute($value)
    {
        if(gettype($value) != 'array')
        {
            $value = explode(',', $value);
        }

        $this->attributes['qr_details'] = json_encode($value);
    }

    public function getCoverImageUrlAttribute()
    {
        return getFileUrl($this->attributes['cover_image']);
    }

    public function setCoverImageAttribute($value)
    {
        if($value != null)
        {
            $this->attributes['cover_image'] = uploadFile($value, 'cover_image');
        }
    }

    public function getLanguageAttribute()
    {

        return array_filter((json_decode($this->attributes['language'], 1) ?? []));
    }

    public function setThemeAttribute($value)
    {

        $this->attributes['theme'] = strtolower($value);
    }

    public function getSearchResult(): SearchResult
    {
        $url = route('restaurant.restaurants.show', $this->id);
        $url .= "|" . route('restaurant.restaurants.edit', $this->id);

        // dd($url, $this->id);
        return new \Spatie\Searchable\SearchResult(
            $this,
            $this->name,
            $url
        );
    }
}
