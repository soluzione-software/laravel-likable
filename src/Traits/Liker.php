<?php /** @noinspection PhpUnused */

namespace SoluzioneSoftware\Laravel\Likable\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use SoluzioneSoftware\Laravel\Likable\Contracts\Likable as LikableContract;
use SoluzioneSoftware\Laravel\Likable\Contracts\Like;
use SoluzioneSoftware\Laravel\Likable\Contracts\Liker as LikerContract;

/**
 * @mixin Model
 */
trait Liker
{
    use ResolvesContracts;

    public function likes(): HasMany
    {
        return $this->hasMany(get_class(static::resolveLikeContract()));
    }

    public function like(LikableContract $likable): bool
    {
        return $this->setLikeRelation($likable, true);
    }

    public function dislike(LikableContract $likable): bool
    {
        return $this->setLikeRelation($likable, false);
    }

    public function removeLike(LikableContract $likable)
    {
        $like = $this->getLikeRelation($likable);
        if ($like){
            $like->delete();
        }
    }

    public function hasLiked(LikableContract $likable): bool
    {
        return $this->hasLikeRelation($likable, true);
    }

    public function hasDisliked(LikableContract $likable): bool
    {
        return $this->hasLikeRelation($likable, false);
    }

    /**
     * @param  LikableContract  $likable
     * @param  bool  $liked
     * @return bool
     */
    public function hasLikeRelation(LikableContract $likable, ?bool $liked = null): bool
    {
        return $this
                ->likes()
                ->where('likable_id', $likable->getKey())
                ->where('likable_type', $likable->getMorphClass())
                ->where(function (Builder $query) use ($liked) {
                    if (!is_null($liked)){
                        $query->where('liked', $liked);
                    }
                })
                ->count() > 0;
    }

    /**
     * @param  LikableContract  $likable
     * @param  bool|null  $liked
     * @return Like|null
     */
    public function getLikeRelation(LikableContract $likable, ?bool $liked = null): ?Like
    {
        /** @var Like|null $like */
        $like = $this
                ->likes()
                ->where('likable_id', $likable->getKey())
                ->where('likable_type', $likable->getMorphClass())
                ->where(function (Builder $query) use ($liked) {
                    if (!is_null($liked)){
                        $query->where('liked', $liked);
                    }
                })
                ->first();

        return $like;
    }

    /**
     * @param  LikableContract  $likable
     * @param  bool  $liked
     * @return bool
     */
    public function setLikeRelation(LikableContract $likable, bool $liked): bool
    {
        $like = $this->getLikeRelation($likable);
        if (!$like){
            $like = static::resolveLikeContract(['liked' => $liked]);
            /** @var LikerContract $this */
            $like->setLiker($this);
            $like->setLikable($likable);
            $like->setLiked($liked);
        }

        if ($like->getLiked() !== $liked){
            $like->setLiked($liked);
        }

        /** @var Like $like */
        return $like->save();
    }
}
