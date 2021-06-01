<?php

namespace App\Filters;

use App\Models\User;

class NoteFilters extends Filters
{
    /**
     * Registered filters to operate upon.
     *
     * @var array
     */
    protected $filters = [
        'search', 'author_id', 'author_public_id'
    ];

    /**
     * Filter the query by text input.
     *
     * @param  string $username
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function search($search)
    {
        return $this->builder->where(function ($query) use ($search) {
            $query->where('title', 'LIKE', '%' . $search . '%')
                ->orWhere('reference', 'LIKE', '%' . $search . '%')
                ->orWhere('content', 'LIKE', '%' . $search . '%')
                ->orWhere('tags', 'LIKE', '%' . $search . '%')
                // user 
                ->orWhereHas('user', function ($q) use ($search) {
                    return $q->where('id', $search);  // change to public key later
                }); 
                // tag
                /*
                ->orWhereHas('tags', function ($q) use ($search) {
                    $tagIds = Tag::where('name', 'LIKE', '%' . $search . '%')->pluck('id');
                    $q->whereIn('tags.id', $tagIds);
                });
                */
        });
    }

    protected function author_id($user_id)
    {
        return $this->builder->where('user_id', $user_id); 
    }

    protected function author_public_id($public_id)
    {
        $user = User::where('public_id', $public_id)->first(); 
        return $this->builder->where('user_id', $user->id); 
    }
}
