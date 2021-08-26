<?php

namespace Illuminated\Database;

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Support\ServiceProvider;
use Illuminated\Database\Http\Middleware\InjectQueries;

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
        if ($this->app->runningInConsole()) {
            (new DbProfiler($this->app))->boot();
        }
    }

    private function registerMiddleware() {
        $kernel = $this->app->make(Kernel::class);
        $kernel->pushMiddleware(InjectQueries::class);
    }
}
