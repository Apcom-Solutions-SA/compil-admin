<?php

namespace App\Filters;
use Spatie\Tags\Tag;

class NoteFilters extends Filters
{
    /**
     * Registered filters to operate upon.
     *
     * @var array
     */
    protected $filters = [
        'search'
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
            // user 
            ->orWhereHas('user', function ($q) use ($search){
                return $q->where('id', $search);  // change to public key later
            })
            // tag
            ->orWhereHas('tags', function ($q) use ($search) {
                $tagIds = Tag::where('name', 'LIKE', '%' . $search . '%')->pluck('id');        
                $q->whereIn('tags.id', $tagIds);
            });
        });
    }

}