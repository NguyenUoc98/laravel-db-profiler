<?php

namespace Illuminated\Database\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminated\Database\DbProfiler;

class InjectQueries
{
    /**
     * @var $dbProfiler
     */
    protected $dbProfiler;

    /**
     * @param DbProfiler $dbProfiler
     */
    public function __construct(DbProfiler $dbProfiler)
    {
        $this->dbProfiler = $dbProfiler;
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($this->dbProfiler->isClear()) {
            $queries = Cache::pull('db_profiler');
            dd(json_decode($queries, true));
        }

        if (!$this->dbProfiler->isEnabled()) {
            return $next($request);
        }

        $this->dbProfiler->boot();

        $response = $next($request);

        return $this->dbProfiler->modifyResponse($response, $request->getRequestUri());
    }
}
