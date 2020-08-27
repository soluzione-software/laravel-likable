<?php

namespace SoluzioneSoftware\Laravel\Likable\Models;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use SoluzioneSoftware\Laravel\Likable\Contracts\Likable;
use SoluzioneSoftware\Laravel\Likable\Contracts\Like as LikeContract;
use SoluzioneSoftware\Laravel\Likable\Contracts\Liker;
use SoluzioneSoftware\Laravel\Likable\Traits\ResolvesContracts;

/**
 * @property string likable_type
 * @property string likable_id
 * @property bool liked
 * @property Likable|Model likable
 * @property Liker liker
 */
class Like extends Model implements LikeContract
{
    use ResolvesContracts;

    protected $casts = [
        'liked' => 'boolean',
    ];

    /**
     * @return BelongsTo
     * @throws BindingResolutionException
     */
    public function liker(): BelongsTo
    {
        $liker = static::resolveLikerContract();
        return $this->belongsTo(get_class($liker), $liker->getForeignKey());
    }

    public function likable(): MorphTo
    {
        return $this->morphTo();
    }

    public function getLiker(): Liker
    {
        return $this->liker;
    }

    /**
     * @param  Liker  $liker
     * @return $this
     */
    public function setLiker(Liker $liker)
    {
        $this->setAttribute($liker->getForeignKey(), $liker->getKey());

        return $this;
    }

    public function getLikable(): Likable
    {
        return $this->likable;
    }

    /**
     * @param  Likable  $likable
     * @return $this
     */
    public function setLikable(Likable $likable)
    {
        $this->likable_type = $likable->getMorphClass();
        $this->likable_id = $likable->getKey();

        return $this;
    }

    public function getLiked(): bool
    {
        return $this->liked;
    }

    public function setLiked(bool $value)
    {
        $this->setAttribute('liked', $value);
    }
}
