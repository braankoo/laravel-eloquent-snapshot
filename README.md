# Eloquent Snapshot

Eloquent Snapshot is a Laravel package that provides functionality for storing and restoring snapshots of Eloquent models. This is useful for tracking changes, creating backups, or implementing undo functionality in your application.

## Installation

Install the package via Composer:

```bash
composer require braankoo/eloquent-snapshot
```

## Usage

### Storing Snapshots

Use the `EloquentSnapshotStoreService` to store snapshots of Eloquent models:

```php

use Braankoo\EloquentSnapshot\EloquentSnapshotFacade as Snapshot;

Snapshot::create($model);
Snapshot::create([$model1, $model2]);
Snapshot::create(collect([$model1, $model2]));
```

### Restoring Snapshots

Use the `EloquentSnapshotRestoreService` to restore snapshots of Eloquent models:

```php

use Braankoo\EloquentSnapshot\EloquentSnapshotFacade as Snapshot;
use Braankoo\EloquentSnapshot\EloquentSnapshotFilter;
Snapshot::restore(Model::first()); // Restore the last model snapshot
Snapshot::restore(Model::first(), (new EloquentSnapshotFilter())->first());
Snapshot::restore(Model::first(), (new EloquentSnapshotFilter())->latest());
```
### Filtering Snapshots

You can filter snapshots using the `EloquentSnapshotFilter` class:

```php
use Braankoo\EloquentSnapshot\EloquentSnapshotFilter;
use Braankoo\EloquentSnapshot\EloquentSnapshotFacade as Snapshot;
Snapshot::restore(Model::first(), (new EloquentSnapshotFilter())->before(Carbon::now()->subDays(7))->after(Carbon::now()->subDays(30))->first());
```
## Features

- Store snapshots of Eloquent models, arrays, or collections.
- Restore snapshots with optional filtering.
- Transactional operations for data integrity.
- Supports chunked inserts for large datasets.

## Configuration

No additional configuration is required. The package uses Laravel's default database connection.
All you have to do is run the migrations to create the necessary tables:

```bash
php artisan migrate
```

## Contributing

Contributions are welcome! Please submit a pull request or open an issue for any bugs or feature requests.

## License

This package is open-source and licensed under the MIT License.
```
