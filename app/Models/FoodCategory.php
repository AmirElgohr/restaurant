<?php

namespace App\Models;

use Spatie\Searchable\Searchable;
use Kyslik\ColumnSortable\Sortable;
use Spatie\Searchable\SearchResult;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FoodCategory extends Model implements Searchable
{
    use HasFactory, Sortable;

    public $table = 'food_categories';

    protected $fillable = [
        'restaurant_id',
        'category_name',
        'category_image',
        'lang_category_name',
        'sort_order',
    ];

    protected $casts = [
        'restaurant_id' => "integer",
        'sort_order' => "integer",
        'category_name' => "string",
        'category_image' => "string",
        'lang_category_name' => "array",
    ];
    public $sortable = [
        'id',
        'category_name',
        'created_at',
        'sort_order',
    ];

    public function getCategoryImageNameAttribute()
    {
        return ucfirst(substr($this->category_name, 0, 1));
    }

    public function getCategoryImageUrlAttribute()
    {
        return getFileUrl($this->attributes['category_image']);
    }

    public function setCategoryImageAttribute($value)
    {
        if ($value != null) {
            if (gettype($value) == 'string') {
                $this->attributes['category_image'] = $value;
            } else {
                $this->attributes['category_image'] = uploadFile($value, 'category_image');
            }
        }
    }
    public function setLangCategoryNameAttribute($value)
    {
        if (gettype($value) == 'array') {
            $this->attributes['lang_category_name'] = json_encode($value);
        }
    }
    public function foods()
    {
        return $this->belongsToMany(Food::class, 'food_food_category')->orderBy('food_food_category.sort_order')->withPivot('sort_order');
    }
    public function getLocalLangNameAttribute()
    {
        if (app()->getLocale() == 'en') {
            return $this->category_name;
        } else {
            return $this->lang_category_name[app()->getLocale()] ?? $this->category_name;
        }
    }
    public function getNameAttribute()
    {
        return $this->category_name;
    }

    public function getSearchResult(): SearchResult
    {
        $url = route('restaurant.food_categories.edit',  $this->id);
        return new \Spatie\Searchable\SearchResult(
            $this,
            $this->name,
            $url
        );
    }
}
