<?php

require_once __DIR__ . "/../vendor/autoload.php";

if( !function_exists('time_diff') ){
    function time_diff($in, $mustIn): float|int
    {
        if( !$in ){
            return 0;
        }
        $in = strtotime($in);
        $mustIn = strtotime($mustIn);
        $diff = $in - $mustIn;
        $diff = $diff / 60;
        return $diff;
    }
}

if( !function_exists('isAbsentJustified') ){
    function isAbsentJustified(string|int|array $user_no, string $date, $isArray=false): string|null|false
    {
        $user = $isArray ? $user_no : getUserWithDemands($user_no, 'accepted');

        if( !$user ){
            return false;
        }

        $active_demands = array_filter($user['demands'], function ($demand) use ($date) {
            return $demand['date_debut'] <= $date && $demand['date_fin'] >= $date;
        });

        if (count($active_demands) > 0) {
            // Set the cell value to 'OK' if user found and date is between start and end date
            $keys = array_keys($active_demands);
            return $active_demands[$keys[0]]['type'];
        } else {
            return null;
        }
    }
}
