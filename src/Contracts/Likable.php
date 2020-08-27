<?php

namespace SoluzioneSoftware\Laravel\Likable\Contracts;

use Illuminate\Database\Eloquent\Relations\MorphMany;

interface Likable extends Model
{
    public function likes(): MorphMany;
}
