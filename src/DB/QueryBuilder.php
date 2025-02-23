<?php

namespace Rayan\Dzgrh\DB;

class QueryBuilder
{
    protected $table;

    protected $select = [];
    protected $where = [];
    protected $join = [];
    protected $orderBy = [];
    protected $groupBy = [];
    protected $limit;
    protected $offset;

    public function table($table)
    {
        $this->table = $table;
        return $this;
    }

    public function select($columns = ['*'])
    {
        $this->select = $columns;
        return $this;
    }

    public function where($column, $operator, $value)
    {
        $this->where[] = "$column $operator ?";
        $this->bindings[] = $value;
        return $this;
    }

    public function join($table, $on, $type = 'INNER')
    {
        $this->join[] = "$type JOIN $table ON $on";
        return $this;
    }

    public function groupBy($columns)
    {
        $this->groupBy = is_array($columns) ? $columns : [$columns];
        return $this;
    }

    public function orderBy($column, $direction = 'ASC')
    {
        $this->orderBy[] = "$column $direction";
        return $this;
    }

    public function limit($limit)
    {
        $this->limit = $limit;
        return $this;
    }

    public function offset($offset)
    {
        $this->offset = $offset;
        return $this;
    }

    public function getQuery()
    {
        $query = "SELECT " . implode(', ', $this->select) . " FROM $this->table";

        if ($this->join) {
            $query .= " " . implode(' ', $this->join);
        }

        if ($this->where) {
            $query .= " WHERE " . implode(' AND ', $this->where);
        }

        if ($this->groupBy) {
            $query .= " GROUP BY " . implode(', ', $this->groupBy);
        }

        if ($this->orderBy) {
            $query .= " ORDER BY " . implode(', ', $this->orderBy);
        }

        if ($this->limit) {
            $query .= " LIMIT $this->limit";
        }

        if ($this->offset) {
            $query .= " OFFSET $this->offset";
        }

        return $query;
    }

    public function getBindings()
    {
        return $this->bindings ?? [];
    }
}
