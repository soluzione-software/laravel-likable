<?php

namespace SoluzioneSoftware\Laravel\Likable\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * @mixin Model
 */
trait Likable
{
    use ResolvesContracts;

    /**
     * @return MorphMany
     */
    public function likes(): MorphMany
    {
        return $this->morphMany(get_class(static::resolveLikeContract()), 'likable');
    }
}
