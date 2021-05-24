<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations; 
use Spatie\Tags\HasTags; 
use App\Filters\NoteFilters;

class Note extends Model
{
    use HasFactory, HasTags, HasTranslations;
    protected $fillable = ['title', 'introduction', 'content', 'user_id', 'reference'];
    public $translatable = ['content'];

    // relationship 
    public function user(){
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
}
