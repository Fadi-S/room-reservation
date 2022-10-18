<?php

namespace App\Queries;

use Illuminate\Database\Eloquent\Builder;

class UserQuery extends Builder {

    public function search($search) : static
    {
        $term = "$search%";

        return $this->where("name", "LIKE", $term);
    }

    public function active($isActive=true) : static
    {
        return $this->where("is_active", "=", $isActive);
    }

}
