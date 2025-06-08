<?php

namespace Braankoo\LaravelEloquentSnapshot;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Braankoo\LaravelEloquentSnapshot\Skeleton\SkeletonClass
 */
class LaravelEloquentSnapshotFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'laravel-eloquent-snapshot';
    }
}
