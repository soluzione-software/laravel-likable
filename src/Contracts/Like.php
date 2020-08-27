<?php

namespace SoluzioneSoftware\Laravel\Likable\Contracts;

interface Like extends Model
{
    public function getLiker(): Liker;

    /**
     * @param  Liker  $liker
     * @return $this
     */
    public function setLiker(Liker $liker);

    /**
     * @return Likable|\Illuminate\Database\Eloquent\Model
     */
    public function getLikable();

    /**
     * @param  Likable  $likable
     * @return $this
     */
    public function setLikable(Likable $likable);

    public function getLiked(): bool;

    public function setLiked(bool $value);
}
