<?php


if( !function_exists('load_env') ){
    function load_env(): array
    {
        $env = parse_ini_file(__DIR__ . '/../.env');
        if( $env ){
            return $env;
        }
        throw new RuntimeException('Unable to load environment.');
    }
}

if( !function_exists('url') ){
    function url( $uri = null )
    {
        if ($uri) {
            return 'http://' . $_SERVER['HTTP_HOST'] . '/rayane/' . trim($uri, '/');
        }
        return 'http://' . $_SERVER['HTTP_HOST'];
    }
}

if( !function_exists('load_db') ){
    function load_db(): PDO|bool
    {
        $path = __DIR__ . '/../db/connection.php';

        if( file_exists($path) ){

            return require($path);
        }else{
            throw new RuntimeException('Unable to load database.');
        }
    }

}

if( !function_exists('redirect') ){
    function redirect($location, int $status_code = 302){
        http_response_code($status_code);
        header('Location:'.$location);
        exit();
    }
}

if( !function_exists('get_department_id') ){
    function get_department_id(string $department): int{
        $db = load_db();

        $sql = "SELECT id FROM departements WHERE code = ?";
        $result = $db->prepare($sql);

        $result->execute([$department]);

        return $result->fetchColumn();
    }
}

if( !function_exists('get_role_id') ){
    function get_role_id(string $role): int{
        $db = load_db();

        $sql = "SELECT id FROM roles WHERE nom = ?";
        $result = $db->prepare($sql);

        $result->execute([$role]);

        return $result->fetchColumn();
    }
}

if( !function_exists('get_service_id') ){
    function get_service_id(string $service): int{
        $db = load_db();

        $sql = "SELECT id FROM services WHERE nom = ?";
        $result = $db->prepare($sql);

        $result->execute([$service]);

        return $result->fetchColumn();
    }
}



if( !function_exists('send_json_response') ){
    function send_json_response(array $response, int $status_code = 201){
        header('Content-Type: application/json');
        http_response_code($status_code);
        echo json_encode($response);
        exit();
    }
}

if( ! function_exists('redirect_back') ){
    function redirect_back(int $status_code = 302){
        http_response_code($status_code);
        header('Location:'. $_SERVER['HTTP_REFERER']);
        exit();
    }
}

