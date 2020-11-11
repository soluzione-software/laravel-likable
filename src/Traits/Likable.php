<?php

namespace SoluzioneSoftware\Laravel\Likable\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use SoluzioneSoftware\Laravel\Likable\Contracts\Like;

/**
 * @mixin Model
 */
trait Likable
{
    use ResolvesContracts;

    /**
     * @return MorphMany|Like
     */
    public function likes()
    {
        return $this->morphMany(get_class(static::resolveLikeContract()), 'likable');
    }
}
