<?php

namespace Rayan\Dzgrh\DB;

use Rayan\Dzgrh\DB;

class Model
{
    protected array $fillable = [];

    public static function create(array $attributes) : static
    {
        DB::table(static::getTable())
    }

    public static function getTable() : string
    {
        return static::$table ?? 
    }
}