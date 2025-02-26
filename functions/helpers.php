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
            return 'http://' . $_SERVER['HTTP_HOST'] . '/' . trim($uri, '/');
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
            throw new RuntimeException('Unable to find database.');
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

if( !function_exists('get_role')){
    function get_role(string $role_id): array{
        $sql = "SELECT * FROM `roles` WHERE id=?";
        $db = load_db();
        $result = $db->prepare($sql);
        $result->execute([$role_id]);
        return $result->fetch(PDO::FETCH_ASSOC);
    }
}

if( !function_exists('get_service')){
    function get_service(string $service_id): array{
        $sql = "SELECT * FROM `services` WHERE id=?";
        $db = load_db();
        $result = $db->prepare($sql);
        $result->execute([$service_id]);
        return $result->fetch(PDO::FETCH_ASSOC);
    }
}

if( !function_exists('get_department')){
    function get_department(string $department_id): array{
        $sql = "SELECT * FROM `departements` WHERE id=?";
        $db = load_db();
        $result = $db->prepare($sql);
        $result->execute([$department_id]);
        return $result->fetch(PDO::FETCH_ASSOC);
    }
}

if( !function_exists('get_address')){
    function get_address(string $address_id): array{
        $sql = "SELECT * FROM `addresses` WHERE id=?";
        $db = load_db();
        $result = $db->prepare($sql);
        $result->execute([$address_id]);
        return $result->fetch(PDO::FETCH_ASSOC);
    }
}


if (!function_exists('redirect_if_not_auth')){
    function redirect_if_not_auth(){
        if (!session_id()){
            session_start();
        }

        if( empty($_SESSION['user_id']) || empty($_SESSION['user']) ){
            session_destroy();
            redirect(url('auth/login.php'));
        }
    }
}

if( !function_exists('fetch_user_information') ){
    function fetch_user_information(string $user_id): array{
        $db = load_db();
        $sql = "SELECT * FROM `employees` WHERE id=?";
        $result = $db->prepare($sql);
        $result->execute([$user_id]);
        $user = $result->fetch(PDO::FETCH_ASSOC);


        $user['role'] = get_role($user['role_id']);
        $user['service'] = get_service($user['service_id']);
        $user['department'] = get_department($user['departement_id']);
        $user['address'] = get_address($user['address_id'] ?? 1);

        return $user;

    }
}

if( !function_exists('join_address') ){
    function join_address(array $address): string{
        $address_str = "%s, %s, %s";

        return sprintf($address_str, $address['address_line'], $address['wilaya'], $address['cite']);
    }
}

if( !function_exists('demand') ){
    function demand(string $user_id, $duree, $description, $date_debut, $date_fin, $info)
    {
        $db = load_db();

        $sql = "INSERT INTO `demands` (`employee_id`, `duree`, `description`, `date_debut`, `date_fin`, `info`, `status`, `date_depose`) 
        VALUES (?, ?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP)";

        $result = $db->prepare($sql);

        $result->execute([$user_id, $duree, $description, $date_debut, $date_fin, $info, 'waiting']);

        return $db->lastInsertId();
    }
}