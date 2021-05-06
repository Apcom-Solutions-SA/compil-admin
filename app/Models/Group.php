<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Group extends Model
{
    use HasFactory;
    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($group) {
            DB::table($group->table_name)->where('group_id', $group->id)->update(['group_id' => null]);
        });
    }
    protected $fillable = ['name', 'description', 'table_name', 'role_id'];
    public $timestamps = false;

    // relationships
    public function users()
    {
        return $this->hasMany('App\User', 'group_id');
    }
}
