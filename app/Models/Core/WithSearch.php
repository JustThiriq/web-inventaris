<?php

namespace App\Models\Core;

trait WithSearch
{
    /**
     * Scope a query to search by name or code.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string|null  $search
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch($query, $search = null)
    {
        if ($search) {
            $query->where(function ($q) use ($search) {
                $searchable = $this->searchable ?? [];

                foreach ($searchable as $field) {
                    if (strpos($field, '.') !== false) {
                        // Handle relational fields like 'category.name'
                        $parts = explode('.', $field);
                        $relation = $parts[0];
                        $relatedField = $parts[1];

                        $q->orWhereHas($relation, function ($query) use ($relatedField, $search) {
                            $query->where($relatedField, 'like', '%'.$search.'%');
                        });
                    } else {
                        // Handle direct model fields
                        $q->orWhere($field, 'like', '%'.$search.'%');
                    }
                }
            });
        }

        return $query;
    }
}
