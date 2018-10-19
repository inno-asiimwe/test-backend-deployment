<?php

namespace App\Providers;

use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        /**
         * Paginate a standard Laravel Collection.
         *
         * @param int $perPage
         * @param int $total
         * @param int $page
         * @param string $pageName
         * @return array
         */
        Collection::macro('paginate', function($perPage, $total = null, $page = null, $pageName = 'page', $options = []) {
            $page = $page ?: LengthAwarePaginator::resolveCurrentPage($pageName);
            $defaultOptions =  [
                'path' => LengthAwarePaginator::resolveCurrentPath(),
                'pageName' => $pageName,
            ];
            $options = array_merge($defaultOptions, $options);
            $lap =  new LengthAwarePaginator(
                $this->forPage($page, $perPage),
                $total ?: $this->count(),
                $perPage,
                $page,
                $options
            );
            return [
                'page' => $lap->currentPage(),
                'payload' => $lap ->values(),
                'firstPageURL' => $lap ->url(1),
                'from' => $lap->firstItem(),
                'pages' => $lap->lastPage(),
                'finalPageURL' => $lap->url($lap->lastPage()),
                'nextPageURL' => $lap->nextPageUrl(),
                'perPage' => $lap->perPage(),
                'prevPageURL' => $lap->previousPageUrl(),
                'to' => $lap->lastItem(),
                'total' => $lap->total(),
            ];
        });
    }
    

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
