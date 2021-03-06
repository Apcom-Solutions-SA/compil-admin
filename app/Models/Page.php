<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations; 

class Page extends Model
{
    use HasFactory;
    use HasTranslations; 
    protected $fillable = ['active', 'footer', 'group_id', 'title', 'introduction', 'content'];
    public $translatable = ['title', 'introduction', 'content'];
    public $timestamps = false;
    protected $attributes = [
        'active' => 1,
        'footer' => 0,
    ];
}
