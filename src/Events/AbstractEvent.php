<?php

namespace SoluzioneSoftware\Laravel\Likable\Events;

use SoluzioneSoftware\Laravel\Likable\Contracts\Like;

abstract class AbstractEvent
{
    /**
     * @var Like
     */
    public $like;

    public function __construct(Like $like)
    {
        $this->like = $like;
    }
}
