<?php

namespace BusinessCentral\Models\Traits;

use Carbon\Carbon;

trait BootExpirationDateTime
{
    public static function bootExpirationDateTime() {
        static::creating(function ($model) {
            $model->expirationDateTime = Carbon::now()->addDays(3);
        });
    }
}