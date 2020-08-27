<?php

namespace SoluzioneSoftware\Laravel\Likable;

use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use SoluzioneSoftware\Laravel\Likable\Contracts\Like as LikeContract;
use SoluzioneSoftware\Laravel\Likable\Models\Like;
use SoluzioneSoftware\Laravel\Likable\Observers\LikeObserver;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * All of the container bindings that should be registered.
     *
     * @var array
     */
    public $bindings = [
        LikeContract::class => Like::class,
    ];

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap the application services.
     * @noinspection PhpUnused
     */
    public function boot()
    {
        $this->migrations();
        $this->observers();
    }

    private function migrations()
    {
        $path = __DIR__ . '/../database/migrations';

        $this->publishes(
            [$path => App::databasePath('migrations')],
            ['likable', 'migrations', 'likable-migrations']
        );

        $this->loadMigrationsFrom($path);
    }

    private function observers()
    {
        Like::observe(LikeObserver::class);
    }
}
