<?php

namespace App\Models;

use Spatie\Searchable\Searchable;
use Kyslik\ColumnSortable\Sortable;
use Spatie\Searchable\SearchResult;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Language extends Model implements Searchable
{
    use HasFactory, Sortable;
    public $table = 'languages';

    protected $fillable = [
        'name',
        'store_location_name',
    ];

    protected $casts = [
        'name' => "string",
        'store_location_name' => "string",
    ];
    public $sortable = [
        'id',
        'name',
        'created_at',
    ];

    public function getNameAttribute()
    {
        return ucfirst($this->attributes['name']);
    }

    public function getSearchResult(): SearchResult
    {
        $url =  route('restaurant.languages.edit',  $this->id);
        // dd($url, $this->id);
        return new \Spatie\Searchable\SearchResult(
            $this,
            $this->name,
            $url
        );
    }
}
