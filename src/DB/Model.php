<?php

namespace Rayan\Dzgrh\DB;

class Model
{
    protected array $fillable = [];

    protected static string $table;

    protected static string $primaryKey = 'id';

    public static function find($id){
        $builder = new QueryBuilder();

        $builder->table(static::$table)
            ->select(static::$table . '.*')
            ->where(static::$primaryKey, '=', $id);
    }
}