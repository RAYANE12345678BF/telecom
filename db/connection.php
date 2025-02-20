<?php

$env = load_env();

try{
    $db = new PDO("mysql:host=localhost;dbname=" . $env['DB_NAME'], $env['DB_USERNAME'], $env['DB_PASSWORD']);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}catch(PDOException $e){
    throw new PDOException($e->getMessage());
}

return $db;