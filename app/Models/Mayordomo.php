<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

class Mayordomo extends User
{
    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::addGlobalScope('mayordomo', function (Builder $builder) {
            $builder->where('rol', 'MAYORDOMO');
        });
    }
}
