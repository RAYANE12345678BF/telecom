<?php

namespace Rayan\Dzgrh;

use Rayan\Dzgrh\DB\QueryBuilder;

class DB
{
    public static function table($table): QueryBuilder
    {
        $builder = new QueryBuilder();
        return $builder->table($table);
    }
}