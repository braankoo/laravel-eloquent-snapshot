<?php

namespace Braankoo\EloquentSnapshot;

use Braankoo\EloquentSnapshot\Models\Snapshot;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class EloquentSnapshotRestoreService
{
    /**
     * Restores a snapshot for the given model.
     *
     * @throws \RuntimeException
     */
    public function restore(Model $model, ?EloquentSnapshotFilter $filter): bool
    {
        $snapshot = Snapshot::query()->filter($model, $filter)->first();

        if (! $snapshot) {
            throw new \RuntimeException('No snapshot found for the given model.');
        }

        try {
            DB::transaction(function () use ($snapshot) {
                $this->updateWithSnapshot($snapshot);
            });
        } catch (\Throwable $e) {
            throw new \RuntimeException('Failed to restore snapshot: '.$e->getMessage());
        }

        return true;
    }

    /**
     * Updates the model with the attributes from the snapshot.
     *
     * @param  Snapshot  $snapshot  The snapshot containing the attributes to restore.
     */
    protected function updateWithSnapshot(Snapshot $snapshot): void
    {
        $modelClass = new $snapshot->model_type;
        $model = $modelClass::findOrFail($snapshot->model_id);
        foreach ($snapshot->attributes as $key => $value) {
            $model->{$key} = $value;
        }
        $model->save();
    }
}
