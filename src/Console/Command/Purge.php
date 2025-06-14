<?php

namespace Braankoo\EloquentSnapshot\Console\Command;

use Braankoo\EloquentSnapshot\Models\Snapshot;
use Illuminate\Console\Command;

class Purge extends Command
{
    protected $signature = 'eloquent-snapshot:purge {--force}';

    protected $description = 'Purge all snapshots from the database';

    public function handle(): int
    {
        if (! $this->option('force')) {
            $this->warn('This command will delete all snapshots from the database.');
            if (! $this->confirm('Do you wish to continue?')) {
                return Command::FAILURE;
            }
        }
        $totalSnapshots = Snapshot::count();

        if ($totalSnapshots === 0) {
            $this->info('No snapshots found to delete.');

            return Command::SUCCESS;
        }

        Snapshot::truncate();

        $this->info("Successfully deleted {$totalSnapshots} snapshots from the database.");

        return Command::SUCCESS;
    }
}
