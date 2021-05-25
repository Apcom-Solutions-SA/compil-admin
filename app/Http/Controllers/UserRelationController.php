<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserRelation;
use App\Models\User; 

class UserRelationController extends Controller
{
    public function index(Request $request, string $user_id, string $attribute){
        $objects_id = UserRelation::where([
            'subject_id' => $user_id, 
            $attribute => 1
        ])->pluck('object_id'); 

        $objects = User::whereIn('id', $objects_id)->get();
        return $objects; 
    }

    public function add(Request $request)
    {
        $request->validate([
            'subject_id' => 'required|exists:users,id',
            'object_id' => 'required|exists:users,id',
            'attribute' => 'required|string'
        ]);

        $object_id = $request->object_id; 

        $relation = UserRelation::firstOrCreate([
            'subject_id' => $request->subject_id,
            'object_id' => $object_id
        ]);

        $attribute = $request->attribute;
        if (!($relation[$attribute])) {
            $relation[$attribute] = 1;
            $relation->save();
        }

        return response()->json([
            'object' => User::find($object_id),
        ]);
    }

    public function remove(Request $request)
    {
        $request->validate([
            'subject_id' => 'required|exists:users,id',
            'object_id' => 'required|exists:users,id',
            'attribute' => 'required|string'
        ]);

        $relation = UserRelation::firstOrCreate([
            'subject_id' => $request->subject_id,
            'object_id' => $request->object_id
        ]);

        $attribute = $request->attribute;
        if ($relation){
            $relation[$attribute] = 0; 
            $relation->save();
        }

        return response()->json([
        ], 200);
    }
}
