<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait Uuids
{
    /**
     * Boot function from laravel.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(static function ($model) {
            $model->{$model->getKeyName()} = (string)Str::uuid();
        });
    }
}
