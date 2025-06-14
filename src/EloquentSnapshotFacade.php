<?php

namespace Braankoo\EloquentSnapshot;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Braankoo\EloquentSnapshot\Skeleton\SkeletonClass
 */
class EloquentSnapshotFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'eloquent-snapshot';
    }
}
