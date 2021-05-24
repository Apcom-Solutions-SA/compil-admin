<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations; 
use Spatie\Tags\HasTags; 

class Note extends Model
{
    use HasFactory, HasTags, HasTranslations;
    protected $fillable = ['title', 'introduction', 'content', 'user_id', 'reference'];
    public $translatable = ['content'];

    // relationship 
    public function user(){
        return $this->belongsTo(User::class); 
    }
}
