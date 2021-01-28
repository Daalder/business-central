<?php

declare(strict_types=1);

namespace Daalder\BusinessCentral\Models\Traits;

use Carbon\Carbon;

trait BootExpirationDateTime
{
    public static function bootExpirationDateTime(): void
    {
        static::creating(static function ($model): void {
            $model->expirationDateTime = Carbon::now()->addDays(3);
        });
    }
}
