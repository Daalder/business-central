<?php

namespace BusinessCentral\Models\Traits;

trait BootClientState
{
    public static function bootClientState() {
        static::creating(function ($model) {
            if(!isset($model->clientState) || null === $model->clientState) {
                $model->clientState = self::generate();
            }
        });
    }

    /**
     * Generate random clientState for Subsciption.
     *
     * @param int $length
     * @return string
     */
    public static function generate($length = 2048)
    {
        return Str::random(2048);
    }
}