<?php /** @noinspection PhpUnused */

namespace SoluzioneSoftware\Laravel\Likable\Traits;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Facades\DB;
use SoluzioneSoftware\Laravel\Likable\Contracts\Likable as LikableContract;
use SoluzioneSoftware\Laravel\Likable\Contracts\Like;
use SoluzioneSoftware\Laravel\Likable\Contracts\Liker as LikerContract;

/**
 * @mixin Model
 * @method static Builder|$this whereHasLikeRelation(LikableContract $likable, ?bool $liked = null)
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
        if ($like) {
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
                    if (!is_null($liked)) {
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
                if (!is_null($liked)) {
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
        if (!$like) {
            $like = static::resolveLikeContract(['liked' => $liked]);
            /** @var LikerContract $this */
            $like->setLiker($this);
            $like->setLikable($likable);
            $like->setLiked($liked);
        }

        if ($like->getLiked() !== $liked) {
            $like->setLiked($liked);
        }

        /** @var Like $like */
        return $like->save();
    }

    /**
     * @param  Builder  $query
     * @param  LikableContract  $likable
     * @param  bool|null  $liked
     * @return Builder
     * @throws BindingResolutionException
     */
    public static function scopeWhereHasLikeRelation(
        Builder $query,
        LikableContract $likable,
        ?bool $liked = null
    ): Builder {
        $likeTable = self::resolveLikeContract()->getTable();
        $liker = self::resolveLikerContract();

        return $query
            ->whereExists(function (QueryBuilder $query) use ($liker, $likeTable, $liked, $likable) {
                $query
                    ->select(DB::raw(1))
                    ->from($likeTable)
                    ->whereRaw("{$liker->getTable()}.{$liker->getKeyName()} = $likeTable.{$liker->getForeignKey()}")
                    ->where("$likeTable.likable_id", $likable->getKey())
                    ->where("$likeTable.likable_type", $likable->getMorphClass())
                    ->where(function (QueryBuilder $query) use ($liked, $likeTable) {
                        if ($liked) {
                            $query->where("$likeTable.liked", $liked);
                        }
                    });
            });
    }
}
