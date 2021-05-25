<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRelation extends Model
{
    use HasFactory;
    protected $fillable = ['subject_id', 'object_id', 'block'];
    public $timestamps = false;
    protected $attributes = [
        'block' => 0,
    ];
}
