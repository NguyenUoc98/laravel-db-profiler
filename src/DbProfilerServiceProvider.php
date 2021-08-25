<?php

namespace Illuminated\Database;

use Illuminate\Support\ServiceProvider;
use Illuminated\Database\Middleware\InjectQueries;

class DbProfilerServiceProvider extends ServiceProvider
{

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/db-profiler.php', 'db-profiler');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'db_profiler');
        $this->registerMiddleware();
    }

    private function registerMiddleware() {
        $router = $this->app['router'];
        $router->pushMiddlewareToGroup('web', InjectQueries::class);
    }
}
