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
    function demand(string $user_id, $duree, $description, $date_debut, $date_fin, $info, $demand_type)
    {
        $db = load_db();

        $sql = "INSERT INTO `demands` (`employee_id`, `type`, `duree`, `description`, `date_debut`, `date_fin`, `info`, `status`, `date_depose`) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP)";

        $result = $db->prepare($sql);

        $result->execute([$user_id, $demand_type, $duree, $description, $date_debut, $date_fin, $info, 'waiting']);

        return $db->lastInsertId();
    }
}

if( !function_exists('read_notification') ){
    function read_notification($id){
        $db = load_db();

        $sql = "UPDATE `notifications` SET `read_state` = ? WHERE `id` = ?";

        $result = $db->prepare($sql);

        $result->execute(['1', $id]);

        return $db->lastInsertId();
    }
}

if( !function_exists('component') ){
    function component(string $path){
        require_once __DIR__ . '/../components/' . trim($path, '/');
    }
}

if( !function_exists('get_roles') ){
    function get_roles(){
        $db = load_db();

        $sql = "SELECT * FROM `roles`";

        $stmt = $db->prepare($sql);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

if( !function_exists('uploadPdf') ){
    function uploadPdf($inputName, $path) {
        if (!isset($_FILES[$inputName]) || $_FILES[$inputName]['error'] !== UPLOAD_ERR_OK) {
            return false; // No file uploaded or an error occurred
        }

        $uploadDir = __DIR__ . '/../storage/' . rtrim($path, '/');

        $fileTmpPath = $_FILES[$inputName]['tmp_name'];
        $fileOriginalName = $_FILES[$inputName]['name'];
        $fileExtension = strtolower(pathinfo($fileOriginalName, PATHINFO_EXTENSION));

        if ($fileExtension !== 'pdf') {
            return false; // Ensure the file is a PDF
        }

        // Generate a unique filename
        $uniqueFileName = uniqid('pdf_', true) . '.pdf';
        $destinationPath = rtrim($uploadDir, '/') . '/' . $uniqueFileName;

        // Ensure the upload directory exists
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Move the uploaded file to the specified directory
        if (move_uploaded_file($fileTmpPath, $destinationPath)) {
            return rtrim($path, '/') . '/' . $uniqueFileName;
        }

        return false; // Return false on failure
    }

}