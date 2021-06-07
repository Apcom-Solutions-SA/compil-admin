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
        'search', 'author_id', 'public_id', 'tag', 'reference'
    ];

    /**
     * Filter the query by text input.
     *
     * @param  string $username
     * @return \Illuminate\Database\Eloquent\Builder
     * 
     * Moteur de recherche
     * Doit être pertinent sur des recherches de: titres, intro, tags, ID notes, clé public autheur
     */
    protected function search($search)
    {
        return $this->builder->where(function ($query) use ($search) {
            $query->where('title', 'LIKE', '%' . $search . '%')
                ->orWhere('introduction', 'LIKE', '%' . $search . '%')
                ->orWhere('reference', 'LIKE', '%' . $search . '%')
                ->orWhere('content', 'LIKE', '%' . $search . '%')
                ->orWhere('tags', 'LIKE', '%' . $search . '%')
                // user 
                ->orWhereHas('user', function ($q) use ($search) {
                    return $q->where('public_id', $search); 
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

    protected function tag(string $tag){
        return $this->builder->where('tags', 'LIKE', '%' . $tag . '%'); 
    }

    protected function reference(string $reference){
        return $this->builder->where('reference', $reference); 
    }

    protected function author_id($user_id)
    {
        return $this->builder->where('user_id', $user_id);
    }

    protected function public_id($public_id)
    {
        $user = User::where('public_id', $public_id)->first();
        return $this->builder->where('user_id', $user->id);
    }
}
