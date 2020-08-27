<?php

namespace SoluzioneSoftware\Laravel\Likable\Contracts;

use Illuminate\Database\Eloquent\Relations\HasMany;

interface Liker extends Model
{
    public function likes(): HasMany;
}
