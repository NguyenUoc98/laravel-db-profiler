<?php

namespace Illuminated\Database;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;

class DbProfiler
{
    /**
     * The query counter.
     *
     * @var int
     */
    private $counter = 1;

    /**
     * @var array
     */
    private static $queries = [];

    /**
     * @var Application
     */
    private $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Boot the service provider.
     *
     * @return void
     *
     */
    public function boot(): void
    {
        if (!$this->isEnabled()) {
            return;
        }
        DB::listen(function (QueryExecuted $query) {
            $i    = $this->counter++;
            $sql  = $this->applyQueryBindings($query->sql, $query->bindings);
            $time = $query->time;
            if ($this->app->runningInConsole()) {
                dump("[{$i}]: {$sql}; ({$time} ms)");
            } else {
                self::$queries[] = "[{$i}]: {$sql}; ({$time} ms)";
            }
        });
    }

    /**
     * Check whether database profiling is enabled or not.
     *
     * @return bool
     */
    public function isEnabled()
    {
        if (!$this->app->isLocal() && !config('db-profiler.force')) {
            return false;
        }

        return $this->app->runningInConsole()
            ? collect($_SERVER['argv'])->contains('-vvv')
            : Request::exists('vvv');
    }

    /**
     * Check is clear cache
     *
     * @return bool
     */
    public function isClear()
    {
        return $this->app->runningInConsole()
            ? collect($_SERVER['argv'])->contains('-vvvv')
            : Request::exists('vvvv');
    }

    /**
     * Apply query bindings to the given SQL query.
     *
     * @param string $sql
     * @param array $bindings
     * @return string
     */
    private function applyQueryBindings(string $sql, array $bindings)
    {
        $bindings = collect($bindings)->map(function ($binding) {
            switch (gettype($binding)) {
                case 'boolean':
                    return (int)$binding;
                case 'string':
                    return "'{$binding}'";
                default:
                    return $binding;
            }
        })->toArray();

        return Str::replaceArray('?', $bindings, $sql);
    }

    /**
     * @param JsonResponse|Response $response
     * @return JsonResponse|Response
     */
    public function modifyResponse($response, $uri)
    {
        $dbProfiler = Cache::pull('db_profiler', []);
        if (!empty($dbProfiler)) {
            $contents = json_decode($dbProfiler, true);
        }
        $contents[$uri] = self::$queries;
        $contents       = json_encode($contents, JSON_UNESCAPED_UNICODE);
        Cache::put('db_profiler', $contents, 60);

        if (!($response instanceof JsonResponse)) {
            $view =  view('db_profiler::alert')->render();
            $contents       = str_replace("</body>", $view . "</body>", $response->getContent());
            $response->setContent($contents);
        }
        return $response;
    }
}
