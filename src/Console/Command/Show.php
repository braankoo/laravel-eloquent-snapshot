<?php

namespace Braankoo\EloquentSnapshot\Console\Command;

use Braankoo\EloquentSnapshot\Models\Snapshot;
use Illuminate\Console\Command;

class Show extends Command
{
    protected $signature = 'eloquent-snapshot:show ';

    protected $description = 'Show all snapshots or snapshots of a specific model';

    public function handle(): int
    {
        $modelTypes = Snapshot::distinct()->pluck('model_type')->toArray();
        $model = $this->choice('Select a model type to view snapshots or press enter to view all', $modelTypes, 0);

        $this->table(
            ['ID', 'Model Type', 'Model ID', 'Attributes', 'Created At'],
            Snapshot::when($model, function ($query) use ($model) {
                return $query->where('model_type', $model);
            })->get()->map(function (Snapshot $snapshot) {
                return [
                    'ID' => $snapshot->id,
                    'Model Type' => $snapshot->model_type,
                    'Model ID' => $snapshot->model_id,
                    'Attributes' => json_encode($snapshot->attributes, JSON_PRETTY_PRINT),
                    'Created At' => $snapshot->created_at->format('Y-m-d H:i:s'),
                ];
            })->toArray()
        );

        return Command::SUCCESS;
    }
}
