<?php

namespace Braankoo\EloquentSnapshot;

use Braankoo\EloquentSnapshot\Models\Snapshot;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class EloquentSnapshotStoreService
{
    /**
     * Validates the input type.
     *
     * @param  Model|array|Collection  $input
     *
     * @throws \InvalidArgumentException
     */
    protected function validateInput($input): void
    {
        if (! $input instanceof Model && ! is_array($input) && ! $input instanceof Collection) {
            throw new \InvalidArgumentException('Input must be an instance of Model, array, or Collection');
        }
    }

    /**
     * Stores snapshots of the given input.
     *
     * @throws \InvalidArgumentException
     */
    public function store(Model|array|Collection $input): bool
    {
        $this->validateInput($input);

        $models = match (true) {
            $input instanceof Model => [$input],
            $input instanceof Collection => $input->all(),
            is_array($input) => $input,
            default => throw new \InvalidArgumentException('Input must be an instance of Model, array, or Collection'),
        };

        $snapshots = array_map(fn (Model $model) => $this->convertToSnapShot($model), $models);

        if (empty($models)) {
            return true;
        }

        return $this->saveSnapshots($snapshots);
    }

    /**
     * Converts a model to a Snapshot instance.
     *
     * @throws \InvalidArgumentException
     */
    private function convertToSnapShot(Model $model): array
    {
        return [
            'model_type' => get_class($model),
            'model_id' => $model->getKey(),
            'attributes' => json_encode($model->getAttributes()),
        ];
    }

    /**
     * Saves an array of snapshots to the database.
     *
     * @param  array<int, array<string, mixed>>  $snapshots
     *
     * @throws \RuntimeException
     */
    protected function saveSnapshots(array $snapshots): bool
    {
        $chunks = array_chunk($snapshots, 500);

        try {
            DB::transaction(function () use ($chunks) {
                foreach ($chunks as $chunk) {
                    Snapshot::insert($chunk);
                }
            });
        } catch (\Throwable $e) {
            throw new \RuntimeException('Failed to save snapshots: '.$e->getMessage());
        }

        return true;
    }
}
