<?php

namespace SoluzioneSoftware\Laravel\Likable\Traits;

use Illuminate\Container\Container;
use Illuminate\Contracts\Container\BindingResolutionException;
use SoluzioneSoftware\Laravel\Likable\Contracts\Likable as LikableContract;
use SoluzioneSoftware\Laravel\Likable\Contracts\Like;
use SoluzioneSoftware\Laravel\Likable\Contracts\Liker as LikerContract;

trait ResolvesContracts
{
    /** @noinspection PhpUnhandledExceptionInspection */
    public static function resolveLikeContract(array $attributes = []): Like
    {
        /** @var Like $binding */
        $binding = Container::getInstance()->make(Like::class, [$attributes]);
        return $binding;
    }

    /**
     * @return LikableContract
     * @throws BindingResolutionException
     */
    public static function resolveLikableContract(): LikableContract
    {
        /** @var LikableContract $binding */
        $binding = Container::getInstance()->make(LikableContract::class);
        return $binding;
    }

    /**
     * @return LikerContract
     * @throws BindingResolutionException
     */
    public static function resolveLikerContract(): LikerContract
    {
        /** @var LikerContract $binding */
        $binding = Container::getInstance()->make(LikerContract::class);
        return $binding;
    }
}
