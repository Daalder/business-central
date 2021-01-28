<?php

declare(strict_types=1);

namespace Daalder\BusinessCentral\Models\Traits;

trait BootClientState
{
    public static function bootClientState(): void
    {
        static::creating(static function ($model): void {
            if (! isset($model->clientState) || $model->clientState === null) {
                $model->clientState = self::generate();
            }
        });
    }

    /**
     * Generate random clientState for Subsciption.
     */
    public static function generate(int $length = 2048): string
    {
        return Str::random(2048);
    }
}
