<?php

namespace App\Queries;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class UserQuery extends Builder
{
    public function search($search): static
    {
        if (!$search) {
            return $this;
        }

        if (is_string($search) && Str::startsWith($search, "#")) {
            return $this->where("users.id", "=", substr($search, 1));
        }

        collect(str_getcsv($search, " ", '"'))
            ->filter()
            ->each(function ($term) {
                $term = "{$term}%";

                $this->where(function ($query) use ($term) {
                    $query
                        ->where("users.name", "like", $term)
                        ->orWhere("users.email", "like", $term)
                        ->orWhere("users.username", "like", $term);
                });
            });

        return $this;
    }

    public function active($isActive = true): static
    {
        return $this->where("is_active", "=", $isActive);
    }
}
