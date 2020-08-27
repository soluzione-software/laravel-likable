<?php

namespace SoluzioneSoftware\Laravel\Likable\Observers;

use Illuminate\Support\Facades\Event;
use SoluzioneSoftware\Laravel\Likable\Events\Disliked;
use SoluzioneSoftware\Laravel\Likable\Events\Liked;
use SoluzioneSoftware\Laravel\Likable\Models\Like;

class LikeObserver
{
    /**
     * @param  Like  $like
     * @return void
     */
    public function created(Like $like)
    {
        Event::dispatch($like->liked ? new Liked($like) : new Disliked($like));
    }

    /**
     * @param  Like  $like
     * @return void
     */
    public function updated(Like $like)
    {
        if ($like->wasChanged('liked')){
            Event::dispatch($like->liked ? new Liked($like) : new Disliked($like));
        }
    }

    /**
     * @param  Like  $like
     * @return void
     */
    public function deleted(Like $like)
    {
        //
    }
}
