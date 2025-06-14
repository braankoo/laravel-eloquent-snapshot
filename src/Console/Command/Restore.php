<?php

namespace Braankoo\EloquentSnapshot\Console\Command;

use Braankoo\EloquentSnapshot\Models\Snapshot;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class Restore extends Command
{
    protected $signature = 'eloquent-snapshot:restore {snapshot*}';

    protected $description = 'Restore snapshots to their original state';

    public function handle(): int
    {
        if (empty($this->argument('snapshot'))) {
            $this->error('No snapshots specified for restoration.');

            return Command::FAILURE;
        }
        $this->warn('This command will restore the specified snapshots to their original state.');
        if (! $this->confirm('Do you wish to continue?')) {
            return Command::FAILURE;
        }
        $snapshots = Snapshot::whereIn('id', $this->argument('snapshot'))->get();
        $bar = $this->output->createProgressBar($snapshots->count());

        try {
            DB::transaction(function () use ($snapshots, $bar) {
                $snapshots->each(function (Snapshot $snapshot) use ($bar) {
                    $this->restoreSnapshot($snapshot);
                    $bar->advance();
                });
            });
        } catch (\Exception $e) {
            $this->error('An error occurred: '.$e->getMessage());

            return Command::FAILURE;
        }

        $this->info("\nSnapshots restored successfully.");
        $bar->finish();

        return Command::SUCCESS;
    }

    protected function restoreSnapshot(Snapshot $snapshot): void
    {
        $modelClass = $snapshot->model_type;
        $modelId = $snapshot->model_id;
        $attributes = $snapshot->attributes;

        $modelTable = (new $modelClass)->getTable();
        $updates = [];
        foreach ($attributes as $key => $value) {
            $updates[$key] = $value;
        }
        DB::table($modelTable)
            ->where('id', $modelId)
            ->update($updates);
    }
}
