<?php

namespace SoluzioneSoftware\Laravel\Likable\Contracts;

use Illuminate\Database\Eloquent\Relations\MorphMany;

interface Likable extends Model
{
    /**
     * @return MorphMany|Like
     */
    public function likes();
}
