<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use App\Filters\NoteFilters;


class Note extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = ['title', 'introduction', 'content', 'user_id', 'reference', 'tags', 'key', 'encryption_key', 'iv'];
    public $translatable = ['title', 'introduction', 'content', 'tags'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['key', 'encryption_key', 'iv'];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['has_key'];

    // relationship 
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Apply all relevant filters.
     *
     * @param  Builder       $query
     * @param  NoteFilters $filters
     * @return Builder
     */
    public function scopeFilter($query, NoteFilters $filters)
    {
        return $filters->apply($query);
    }

    public function getHasKeyAttribute()
    {
        return !!$this->key;
    }
}
