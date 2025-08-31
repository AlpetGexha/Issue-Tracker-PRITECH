<?php

namespace App\Traits;

trait HasUserId
{
    protected static function bootHasUserId()
    {
        static::creating(function ($model) {
            if (auth()->check()) {
                $model->user_id = auth()->id();
            }
        });
    }
}
