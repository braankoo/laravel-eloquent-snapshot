<?php

namespace Braankoo\EloquentSnapshot\Models;

use Braankoo\EloquentSnapshot\EloquentSnapshotFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Snapshot extends \Illuminate\Database\Eloquent\Model
{
    protected $fillable = [
        'model_type',
        'model_id',
        'attributes',
    ];

    protected $casts = [
        'attributes' => 'array',
    ];

    public function getModelIdAttribute($value)
    {
        return (int) $value;
    }

    public function scopeFilter(Builder $query, Model $model, ?EloquentSnapshotFilter $filter): Builder
    {
        return $query->where('model_type', '=', get_class($model))
            ->where('model_id', $model->id)
            ->when($filter, function ($query) use ($filter) {
                return $filter->apply($query);
            })
            ->when(! $filter, function ($query) {
                return $query->orderBy('created_at', 'desc');
            });
    }
}
