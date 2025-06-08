<?php

namespace Braankoo\LaravelEloquentSnapshot\Models;

class Snapshot extends \Illuminate\Database\Eloquent\Model
{
    protected $fillable = [
        'model_type',
        'model_id',
        'attributes',
        'relations',
    ];

    protected $casts = [
        'attributes' => 'json',
        'relations' => 'json',
    ];

    public function getModelTypeAttribute($value)
    {
        return class_basename($value);
    }

    public function getModelIdAttribute($value)
    {
        return (int) $value;
    }
}
