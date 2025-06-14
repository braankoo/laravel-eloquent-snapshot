<?php

namespace Braankoo\EloquentSnapshot;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class EloquentSnapshot
{
    public function __construct(
        protected EloquentSnapshotStoreService $eloquentSnapshotStoreService,
        protected EloquentSnapshotRestoreService $eloquentRestoreSnapshotService
    ) {}

    /**
     * @param  Model|Model[]|Collection<Model>  $input
     */
    public function create(Model|array|Collection $input): bool
    {
        return $this->eloquentSnapshotStoreService->store($input);

    }

    public function restore(Model $model, ?EloquentSnapshotFilter $filter = null): bool
    {
        return $this->eloquentRestoreSnapshotService->restore($model, $filter);
    }
}
