<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Tags\HasTags; 

class Note extends Model
{
    use HasFactory, HasTags;
    protected $fillable = ['title', 'introduction', 'content', 'user_id', 'reference'];

    // relationship 
    public function user(){
        return $this->belongsTo(User::class); 
    }
}
