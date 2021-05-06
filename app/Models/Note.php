<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'introduction', 'body', 'user_id'];

    // relationship 
    public function user(){
        return $this->belongsTo(User::class); 
    }
}
