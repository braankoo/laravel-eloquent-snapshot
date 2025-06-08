<?php

namespace Braankoo\LaravelEloquentSnapshot;

use Illuminate\Database\Eloquent\Model;
use Braankoo\LaravelEloquentSnapshot\Models\Snapshot;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class LaravelEloquentSnapshot
{

    private function covertToSnapShot(Model $model): Snapshot
    {
        if (!$model instanceof \Illuminate\Database\Eloquent\Model) {
            throw new \InvalidArgumentException('The provided model must be an instance of Illuminate\Database\Eloquent\Model');
        }
        return new Snapshot([
            'model_type' => get_class($model),
            'model_id' => $model->getKey(),
            'attributes' => $model->getAttributes(),
            'relations' => $model->getRelations(),
            'exists' => $model->exists,
            'id' => $model->getKey(),
        ]);
    }

    public function create(Model|array|Collection $input): bool
    {
        if (!$input instanceof Model && !is_array($input) && !$input instanceof Collection) {
            throw new \InvalidArgumentException('Input must be an instance of Model, array, or Collection');
        }
        if ($input instanceof Model) {
            $snapshots[] = $this->covertToSnapShot($input);
        } elseif ($input instanceof Collection) {
            $snapshots = $input->map(fn ($model) => $this->covertToSnapShot($model))->toArray();
        } elseif (is_array($input)) {
            $snapshots = array_map(fn ($model) => $this->covertToSnapShot($model), $input);
        }

        return $this->saveSnapshots($snapshots);
    }

    /**
     * Create multiple snapshots from an array of models.
     *
     * @param Snapshot[] $snapshots
     * @return bool
     */
    protected function saveSnapshots(array $snapshot): bool
    {
        $chunk = array_chunk($snapshot, 500);
        DB::transaction(function () use ($chunk) {
            foreach ($chunk as $snapshots) {
                Snapshot::insert($snapshots);
            }
        })->onError(function (\Throwable $e) {
            throw new \RuntimeException('Failed to save snapshots: ' . $e->getMessage());
        });
        return true;
    }
}
