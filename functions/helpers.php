<?php


if (!function_exists('load_env')) {
    function load_env(): array
    {
        $env = parse_ini_file(__DIR__ . '/../.env');
        if ($env) {
            return $env;
        }
        throw new RuntimeException('Unable to load environment.');
    }
}
if (!function_exists('fetch_user_demands')) {
    function fetch_user_demands($user_id, $type): array|null
    {
        $pdo = load_db();
        $sql = "SELECT * FROM `demands` WHERE status=? AND type=? AND `date_depose` >= DATE_FORMAT(CURDATE(), '%Y-01-01') AND employee_id=?";

        $stmt = $pdo->prepare($sql);

        $stmt->execute(['accepted', $type, $user_id]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

if (!function_exists('url')) {
    function url($uri = null)
    {
        if ($uri) {
            return 'http://' . $_SERVER['HTTP_HOST'] . '/' . trim($uri, '/');
        }
        return 'http://' . $_SERVER['HTTP_HOST'];
    }
}

if (!function_exists('load_db')) {
    function load_db(): PDO|bool
    {
        $path = __DIR__ . '/../db/connection.php';

        if (file_exists($path)) {

            return require($path);
        } else {
            throw new RuntimeException('Unable to find database.');
        }
    }
}

if (!function_exists('redirect')) {
    function redirect($location, int $status_code = 302)
    {
        if (http_response_code() != 302) {
            http_response_code($status_code);
        }
        header('Location:' . $location);
        exit();
    }
}

if( !function_exists('asset') ){
    function asset(string $path, $url=true){
        if (!$path)
            throw new \Exception('error in path to storage');
        return $url ? url('assets/' . $path) : __DIR__ . '/../assets/' . $path;
    }
}

if (!function_exists('get_department_id')) {
    function get_department_id(string $department): int
    {
        $db = load_db();

        $sql = "SELECT id FROM departements WHERE code = ?";
        $result = $db->prepare($sql);

        $result->execute([$department]);

        return $result->fetchColumn();
    }
}

if (!function_exists('get_role_id')) {
    function get_role_id(string $role): int
    {
        $db = load_db();

        $sql = "SELECT id FROM roles WHERE nom = ?";
        $result = $db->prepare($sql);

        $result->execute([$role]);

        return $result->fetchColumn();
    }
}

if (!function_exists('get_service_id')) {
    function get_service_id(string $service): int
    {
        $db = load_db();

        $sql = "SELECT id FROM services WHERE nom = ?";
        $result = $db->prepare($sql);

        $result->execute([$service]);

        return $result->fetchColumn();
    }
}

if (!function_exists('send_json_response')) {
    function send_json_response(array $response, int $status_code = 201)
    {
        header('Content-Type: application/json');
        http_response_code($status_code);
        echo json_encode($response);
        exit();
    }
}

if (! function_exists('redirect_back')) {
    function redirect_back(int $status_code = 302)
    {
        if ($status_code != -1) {
            http_response_code($status_code);
        }
        header('Location:' . $_SERVER['HTTP_REFERER']);
        exit();
    }
}

if (!function_exists('get_role')) {
    function get_role(string $role_id): array
    {
        $sql = "SELECT * FROM `roles` WHERE id=?";
        $db = load_db();
        $result = $db->prepare($sql);
        $result->execute([$role_id]);
        return $result->fetch(PDO::FETCH_ASSOC);
    }
}

if (!function_exists('get_service')) {
    function get_service(string $service_id): array
    {
        $sql = "SELECT * FROM `services` WHERE id=?";
        $db = load_db();
        $result = $db->prepare($sql);
        $result->execute([$service_id]);
        return $result->fetch(PDO::FETCH_ASSOC);
    }
}

if (!function_exists('get_department')) {
    function get_department(string $department_id): array
    {
        $sql = "SELECT * FROM `departements` WHERE id=?";
        $db = load_db();
        $result = $db->prepare($sql);
        $result->execute([$department_id]);
        return $result->fetch(PDO::FETCH_ASSOC);
    }
}

if (!function_exists('get_address')) {
    function get_address(string $address_id): array
    {
        $sql = "SELECT * FROM `addresses` WHERE id=?";
        $db = load_db();
        $result = $db->prepare($sql);
        $result->execute([$address_id]);
        return $result->fetch(PDO::FETCH_ASSOC) ?? [];
    }
}

if (!function_exists('redirect_if_not_auth')) {
    function redirect_if_not_auth()
    {
        if (!session_id()) {
            session_start();
        }

        if (empty($_SESSION['user_id']) || empty($_SESSION['user'])) {
            session_destroy();
            redirect(url('auth/login.php'));
        }
    }
}

if (!function_exists('join_address')) {
    function join_address(array $address): string
    {
        if (empty($address)) {
            return "";
        }
        $address_str = "%s, %s, %s";

        return sprintf($address_str, $address['address_line'], $address['wilaya'], $address['cite']);
    }
}

if (!function_exists('demand')) {
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

if (!function_exists('read_notification')) {
    function read_notification($id)
    {
        $db = load_db();

        $sql = "UPDATE `notifications` SET `read_state` = ? WHERE `id` = ?";

        $result = $db->prepare($sql);

        $result->execute(['1', $id]);

        return $db->lastInsertId();
    }
}

if (!function_exists('get_roles')) {
    function get_roles()
    {
        $db = load_db();

        $sql = "SELECT * FROM `roles`";

        $stmt = $db->prepare($sql);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

if (!function_exists('uploadPdf')) {
    function uploadPdf($inputName, $path)
    {
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

if (!function_exists('get_services')) {

    function get_services()
    {
        $db = load_db();

        $sql = "SELECT * FROM `services`";

        $stmt = $db->prepare($sql);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

if (!function_exists('get_departments')) {
    function get_departments()
    {
        $db = load_db();

        $sql = "SELECT * FROM `departements`";

        $stmt = $db->prepare($sql);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

if (!function_exists('insert_support')) {
    function insert_support($user_id, $message, $type)
    {
        $db = load_db();

        $sql = "INSERT INTO `support` (`employee_id`, `type`, `message`, `date_depose`) VALUES (?, ?, ?, NOW())";

        $stmt = $db->prepare($sql);

        $stmt->execute([$user_id, $type, $message]);
    }
}

if (!function_exists('get_demand_with_lifecycle')) {
    function get_demand_with_lifecycle($demand_id)
    {

        $pdo = load_db();
        // Get the demand details
        $stmt = $pdo->prepare("SELECT * FROM demands WHERE id = ?");
        $stmt->execute([$demand_id]);
        $demand = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$demand) {
            send_json_response(["success" => false, "message" => "Demand not found"]);
            return;
        }

        // Get the lifecycle details
        $stmt = $pdo->prepare("SELECT * FROM `demand_lifecycle` WHERE demand_id = ?");
        $stmt->execute([$demand_id]);
        $lifecycle = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Add lifecycle to the demand array
        $demand['lifecycle'] = $lifecycle;

        return $demand;
    }
}

if (!function_exists('get_all_demands_with_lifecycle')) {
    function get_all_demands_with_lifecycle($user_id = null)
    {

        $pdo = load_db();
        // Step 1: Fetch all demands
        $stmt = $pdo->prepare("SELECT * FROM demands WHERE `status`=? " . ($user_id ? "AND `employee_id`=?" : ""));
        $stmt->execute($user_id ? ['waiting', $user_id] : ['waiting']);
        $demands = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Step 2: Attach lifecycle to each demand
        foreach ($demands as &$demand) {
            $demand_id = $demand['id'];

            // Fetch lifecycle for the current demand
            $lifecycle_stmt = $pdo->prepare("SELECT * FROM demand_lifecycle WHERE demand_id = ?");
            $lifecycle_stmt->execute([$demand_id]);
            $demand['lifecycle'] = $lifecycle_stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        return ["success" => true, "demands" => $demands];
    }
}

if (!function_exists('add_lifecycle_entry')) {

    function add_lifecycle_entry($demand_id, $superior_id)
    {

        $pdo = load_db();
        // Prepare SQL statement
        $stmt = $pdo->prepare("INSERT INTO demand_lifecycle (`demand_id`, `superior_id`, `took_at`) VALUES (?, ?, NOW())");

        // Execute the query
        if ($stmt->execute([$demand_id, $superior_id])) {
            return ["success" => true, "message" => "Lifecycle entry added"];
        } else {
            return ["success" => false, "message" => "Failed to add lifecycle entry"];
        }
    }
}

if( !function_exists('push_demand_status') ){
    function push_demand_status($demand_id){
        $pdo = load_db();

        $demand = fetch_demand($demand_id);

        $title = 'creation status';
        $description = 'un decision de votre demands de conge a ete deposer';

        $sql = "INSERT INTO `notifications` (`employee_id`, `title`, `description`,`url` ) VALUES (?, ?, ?, ?)";

        $stmt = $pdo->prepare($sql);

        try {
            $stmt->execute([$demand['employee_id'], $title, $description, url('dashboard/demands/list.php')]);
        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }

    }
}

if (!function_exists('set_decision')) {
    function set_decision($demand_id, $superior_id, $decision)
    {
        $pdo = load_db();

        $demand = fetch_demand($demand_id);

        $sql = "UPDATE `demand_lifecycle` SET `decision`=?, `took_at`=NOW() WHERE `demand_id`=? AND `superior_id`=?";

        $stmt = $pdo->prepare($sql);

        // Execute the query
        if ($stmt->execute([$decision, $demand_id, $superior_id])) {
            //add it to the up worker or if is the director just this is the final decision
            if (fetch_user_information($superior_id)['role']['nom'] == 'Directeur') {
                $sql = "UPDATE `demands` SET `status`=? WHERE `id`=?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$decision, $demand_id]);


                if ($demand['type'] == 'conge_rc' && $decision == 'accepted') {

                    $response = deduct_leave_days($demand['employee_id'], $demand['duree']);
                    //die(var_dump($response));
                    if (!$response['success']) {
                        return ["success" => false, "message" => $response['message']];
                    }
                }
            } else {
                $superior_id = fetch_user_information($superior_id)['superior_id'];

                if( is_in_conge($superior_id) ){
                    $superior = get_substitue($superior_id);
                    $superior_id = $superior['id'];
                }

                add_lifecycle_entry($demand_id, $superior_id);
                push_demand_creation_notification($demand['id']);
            }
            push_demand_status($_POST['demand_id']);
            return ["success" => true, "message" => "Lifecycle entry updated"];
        } else {
            return ["success" => false, "message" => "Failed to add lifecycle entry"];
        }
    }
}

if (!function_exists('add_work_day')) {
    function add_work_day($employee_id, $date, $benefited)
    {

        $pdo = load_db();
        // Ensure the date is in YYYY-MM-DD format
        $formatted_date = date('Y-m-d', strtotime($date));

        // Prepare SQL statement
        $stmt = $pdo->prepare("INSERT INTO work_days (employee_id, date, benefited) VALUES (?, ?, ?)");

        // Execute the query
        if ($stmt->execute([$employee_id, $formatted_date, $benefited ? 1 : 0])) {
            return ["success" => true, "message" => "Work day entry added"];
        } else {
            return ["success" => false, "message" => "Failed to add work day entry"];
        }
    }
}

if (!function_exists('remove_work_day')) {
    function remove_work_day($employee_id, $date)
    {
        $pdo = load_db();
        // Ensure the date is in YYYY-MM-DD format
        $formatted_date = date('Y-m-d', strtotime($date));

        // Prepare SQL statement
        $stmt = $pdo->prepare("DELETE FROM work_days WHERE employee_id = ? AND date = ?");

        // Execute the query
        if ($stmt->execute([$employee_id, $formatted_date])) {
            if ($stmt->rowCount() > 0) {
                return ["success" => true, "message" => "Work day entry removed"];
            } else {
                return ["success" => false, "message" => "No entry found for the given employee and date"];
            }
        } else {
            return ["success" => false, "message" => "Failed to remove work day entry"];
        }
    }
}

if (!function_exists('fetch_work_days')) {
    function fetch_work_days($employee_id)
    {

        $pdo = load_db();
        // Prepare SQL statement
        $stmt = $pdo->prepare("SELECT * FROM work_days WHERE employee_id = ? ORDER BY date ASC");
        $stmt->execute([$employee_id]);

        // Fetch results as an associative array
        $work_days = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Return the result
        return ["success" => true, "data" => $work_days];
    }
}

if (!function_exists('calculate_rc_days')) {
    function calculate_rc_days($employee_id)
    {

        $pdo = load_db();
        // Prepare SQL statement to fetch work days
        $stmt = $pdo->prepare("SELECT date, benefited FROM work_days WHERE employee_id = ?");
        $stmt->execute([$employee_id]);
        $work_days = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $rc_days = 0;

        foreach ($work_days as $day) {
            $date = $day['date'];
            $benefited = $day['benefited'];

            // Get the day of the week (0 = Sunday, 5 = Friday, 6 = Saturday)
            $day_of_week = date('w', strtotime($date));

            if ($benefited == 0) {
                if ($day_of_week == 5) { // Friday
                    $rc_days += 2;
                } elseif ($day_of_week == 6) { // Saturday
                    $rc_days += 1;
                }
            }
        }

        return ["success" => true, "rc_days" => $rc_days];
    }
}

if (!function_exists('fetch_user_missions')) {
    function fetch_user_missions($user_id)
    {
        $pdo = load_db();
        $sql = "SELECT * FROM `demands` WHERE `employee_id`=? AND `type`=?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$user_id, 'mission']);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map(function ($demand) {
            $demand['info'] = json_decode($demand['info'], true);
            return $demand;
        }, $results);
    }
}

if (!function_exists('dd')) {
    function dd(...$args)
    {
        var_dump(...$args);
        exit();
    }
}

if (!function_exists('fetch_user_deplacements')) {
    function fetch_user_deplacements($user_id)
    {
        $pdo = load_db();
        $sql = "SELECT * FROM `demands` WHERE `employee_id`=? AND `type`=?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$user_id, 'deplacement']);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map(function ($demand) {
            $demand['info'] = json_decode($demand['info'], true);
            return $demand;
        }, $results);
    }
}

if (!function_exists('fetch_user_leaves')) {
    function fetch_user_leaves($user_id)
    {
        $pdo = load_db();
        $sql = "SELECT * FROM `demands` WHERE `employee_id`=? AND `type`=?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$user_id, 'leave']);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map(function ($demand) {
            $demand['info'] = json_decode($demand['info'], true);
            return $demand;
        }, $results);
    }
}

if (!function_exists('deduct_leave_days')) {
    function deduct_leave_days($employee_id, $requested_days)
    {
        $pdo = load_db();

        // Step 1: Calculate available RC days
        $rc_response = calculate_rc_days($employee_id);
        if (!$rc_response['success']) {
            return ["success" => false, "message" => "Failed to calculate RC days"];
        }

        $available_rc_days = $rc_response['rc_days'];

        // Step 2: Check if the employee has enough RC days
        if ($available_rc_days < $requested_days) {
            return ["success" => false, "message" => "Not enough RC days available"];
        }

        // Step 3: Fetch available Fridays and Saturdays (unbenefited) for deduction
        $stmt = $pdo->prepare("SELECT date, benefited FROM work_days WHERE employee_id = ? AND benefited = 0 ORDER BY date ASC");
        $stmt->execute([$employee_id]);
        $work_days = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $days_deducted = 0;
        foreach ($work_days as $day) {
            if ($days_deducted >= $requested_days) break; // Stop if we've deducted enough

            $date = $day['date'];
            $day_of_week = date('w', strtotime($date)); // 5 = Friday, 6 = Saturday

            if ($day_of_week == 5 && $days_deducted + 2 <= $requested_days) {
                // Friday, deduct 2 days if possible
                $update_stmt = $pdo->prepare("UPDATE work_days SET benefited = 1 WHERE employee_id = ? AND date = ?");
                $update_stmt->execute([$employee_id, $date]);
                $days_deducted += 2;
            } elseif ($day_of_week == 6 && $days_deducted + 1 <= $requested_days) {
                // Saturday, deduct 1 day
                $update_stmt = $pdo->prepare("UPDATE work_days SET benefited = 1 WHERE employee_id = ? AND date = ?");
                $update_stmt->execute([$employee_id, $date]);
                $days_deducted += 1;
            }
        }

        if ($days_deducted < $requested_days) {
            return ["success" => false, "message" => "Not enough available Fridays and Saturdays to deduct requested leave"];
        }

        return ["success" => true, "message" => "Leave deducted successfully", "days_deducted" => $days_deducted];
    }
}

if (!function_exists('fetch_compte_rendu')) {
    function fetch_compte_rendu($demand_id)
    {
        $db = load_db();

        $sql = "SELECT * FROM `compte_rendus` WHERE `demand_id`=?";

        $stmt = $db->prepare($sql);

        $stmt->execute([$demand_id]);

        if ($stmt->rowCount() < 1) {
            return null;
        }

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

if (!function_exists('fetch_demand')) {
    function fetch_demand($demand_id)
    {
        $db = load_db();

        $sql = "SELECT * FROM `demands` WHERE `id`=?";

        $stmt = $db->prepare($sql);

        $stmt->execute([$demand_id]);

        $demand = $stmt->fetch(PDO::FETCH_ASSOC);

        $demand['info'] = json_decode($demand['info'], true);

        $demand['compte_rendu'] = fetch_compte_rendu($demand_id);
        if ($demand['compte_rendu']) {
            $demand['compte_rendu']['info'] = json_decode($demand['compte_rendu']['info'], true);
        }

        return $demand;
    }
}

if (!function_exists('create_compte_rendu')) {
    function create_compte_rendu($demand_id, $info)
    {
        $db = load_db();

        $sql = "INSERT INTO `compte_rendus` (`demand_id`, `info`, `created_at`, `updated_at`) VALUES (?, ?, NOW(), NOW())";

        $stmt = $db->prepare($sql);

        $stmt->execute([$demand_id, $info]);
    }
}

if (!function_exists('update_compte_rendu')) {
    function update_compte_rendu($demand_id, $info)
    {
        $db = load_db();

        $sql = "UPDATE `compte_rendus` SET `info` = ?, `updated_at` = NOW() WHERE `demand_id` = ?";

        $stmt = $db->prepare($sql);

        $stmt->execute([$info, $demand_id]);
    }
}

if (!function_exists('handle_role')) {
    function handle_role(callable $callback)
    {
        if (!session_id()) {
            session_start();
        }
        $user = fetch_user_information($_SESSION['user_id'], false);

        return $callback($user['role']['nom']);
    }
}

if (!function_exists('dashboard_url')) {
    function dashboard_url($uri, $prefix = 'dashboard')
    {
        //dd(url(rtrim(rtrim($prefix) . '/' . rtrim($uri), '/')));
        return url(rtrim(rtrim($prefix) . '/' . rtrim($uri), '/'));
    }
}

if (!function_exists('if_user_is')) {
    function if_user_is(string|array $role, ?callable $callback = null)
    {
        if (!session_id()) {
            session_start();
        }

        if (is_string($role)) {
            $role = [$role];
        }
        $user = fetch_user_information($_SESSION['user_id'], false);
        foreach ($role as $r) {
            if ($user['role']['nom'] == $r) {
                if ($callback) {
                    return $callback();
                }

                return true;
            }
        }

        return false;
    }
}

if (!function_exists('can_do_conge')) {
    function can_do_conge_annual($user_id, $duree)
    {
        $pdo = load_db();

        $sql = "SELECT * FROM `demands` WHERE `employee_id`=? AND `type`=? AND status=?";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([$user_id, 'conge_annual', 'accepted']);

        if( $stmt->rowCount() < 1 ){
            return true;
        }

        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this_year_conges = array_filter($results, function($result){
            return date('Y', strtotime($result['date_debut'])) == date('Y');
        });

        $sum = 0;

        foreach ($this_year_conges as $conge){
            $sum += $diff = daysBetweenDates($conge['date_debut'], $conge['date_fin']);
        }

        return (30 - $sum) >= $duree;
    }
}

if (!function_exists('can_do_conge')) {
    function can_do_conge($user_id, string $conge_type)
    {
        $pdo = load_db();

        $sql = "SELECT * FROM `demands` WHERE `employee_id`=? AND `type`=? AND (`status`=? OR (`status`=? AND  `date_fin`<=NOW()))";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([$user_id, $conge_type, 'waiting', 'accepted']);

        return $stmt->rowCount() < 1;
    }
}

if (!function_exists('convertToAscii')) {
    function convertToAscii($column)
    {
        $ascii = 0;
        for ($i = 0; $i < strlen($column); $i++) {
            $ascii = $ascii * 26 + (ord($column[$i]) - ord('A') + 1);
        }
        return $ascii;
    }
}

if (!function_exists('convertFromAscii')) {
    // convert to A from it's ascci number
    function convertFromAscii($number)
    {
        $column = '';
        while ($number > 0) {
            $number--;
            $column = chr($number % 26 + ord('A')) . $column;
            $number = (int)($number / 26);
        }
        return $column;
    }
}

if (!function_exists('getUserWithDemands')) {
    function getUserWithDemands($user_no, $status = 'waiting')
    {
        $db = load_db();

        //get user id with nom
        $sql = "SELECT `id` FROM `employees` WHERE `matricule`=?";

        $stmt = $db->prepare($sql);

        $stmt->execute([$user_no]);

        if ($stmt->rowCount() < 1) {
            return null;
        }

        $user_id = $stmt->fetch(PDO::FETCH_ASSOC)['id'];

        $sql = "SELECT * FROM `demands` WHERE `employee_id`=? AND `status`=?";

        $stmt = $db->prepare($sql);

        $stmt->execute([$user_id, $status]);

        $user = [
            'user_id' => $user_id,
            'demands' => $stmt->fetchAll(PDO::FETCH_ASSOC)
        ];

        return $user;
    }
}


if (!function_exists('storage_path')) {
    function storage_path(string $path = '/', bool $url = false)
    {
        if (!$path)
            throw new \Exception('error in path to storage');
        return $url ? url('storage/' . $path) : __DIR__ . '/../storage/' . rtrim($path, "/");
    }
}

if (!function_exists('insertMultipleRows')) {
    function insertMultipleRows(string $table, array $rows): bool
    {
        $pdo = load_db();
        
        if (empty($rows)) return false;

        // Extract column names from the first row
        $columns = array_keys($rows[0]);
        $placeholders = [];
        $values = [];

        // Prepare placeholders and values
        foreach ($rows as $row) {
            $placeholders[] = '(' . rtrim(str_repeat('?, ', count($columns)), ', ') . ')';
            foreach ($columns as $col) {
                $values[] = $row[$col] ?? null;
            }
        }

        // Build query
        $sql = "INSERT INTO `$table` (" . implode(', ', $columns) . ") VALUES " . implode(', ', $placeholders);

        // Execute the query
        $stmt = $pdo->prepare($sql);
        return $stmt->execute($values);
    }
}

if( !function_exists('get_user_with_role') ){
    function get_user_with_role($role){
        $db = load_db();

        $sql = "SELECT * FROM `roles` WHERE nom=?";

        $stmt = $db->prepare($sql);

        $stmt->execute([$role]);

        $sql = "SELECT * FROM `employees` WHERE `role_id`=?";

        $etmt = $db->prepare($sql);

        $etmt->execute([$stmt->fetch(PDO::FETCH_ASSOC)['id']]);

        $users = $etmt->fetch(PDO::FETCH_ASSOC);

        return $users;
    }
}

if( !function_exists('push_notifiation') ){
    function push_notifiation(array $data){
        $db = load_db();

        $sql = "INSERT INTO `notifications` (`employee_id`, `title`, `description`,`url` ) VALUES (?, ?, ?, ?)";

        $stmt = $db->prepare($sql);

        try {
            $stmt->execute([$data['user_id'], $data['title'], $data['body'],$data['url']]);
        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }

    }
}

if( !function_exists('push_demand_creation_notification') ){
    function push_demand_creation_notification($demand_id){
        $db = load_db();

        $demand = get_demand_with_lifecycle($demand_id);
        $employee = get_user($demand['employee_id']);

        $target_user = null;

        $current_life = array_filter($demand['lifecycle'], function($value){
            return $value['decision'] == 'waiting';
        });


        $keys = array_keys($current_life);

        if(count($keys) === 0){
            return null;
        }

        $key = $keys[0];

        $current_life = $current_life[$key];
        $target_user_id = $current_life['superior_id'];


        $title = 'creation demand from ' . $employee['nom'] . ' ' . $employee['prenom'];
        $description = 'il y a une nouvelle demande de creation de conge';

        $sql = "INSERT INTO `notifications` (`employee_id`, `title`, `description`,`url` ) VALUES (?, ?, ?, ?)";

        $stmt = $db->prepare($sql);

        try {
            $stmt->execute([$target_user_id, $title, $description, url('dashboard/demands/consulte.php')]);
        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }
}

if( !function_exists('get_absences') ){
    function get_absences($employee_id, $year = null, $month = null) {
        $db = load_db();
        
        $query = "SELECT a.* FROM absenses a 
                  INNER JOIN employees e ON a.employee_matricule = e.matricule 
                  WHERE e.id = ?";
        
        $params = [$employee_id];
        
        if ($year && $month) {
            $query .= " AND YEAR(a.date) = ? AND MONTH(a.date) = ?";
            $params[] = $year;
            $params[] = $month;
        }
        
        $query .= " ORDER BY a.date DESC";
        
        $stmt = $db->prepare($query);
        $stmt->execute($params);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}





if( !function_exists('countDaysInMonthBetweenDates') ){
function countDaysInMonthBetweenDates(string $startDate, string $endDate, int $targetMonth): int
{
    $start = DateTime::createFromFormat('m-d-Y', $startDate);
    $end = DateTime::createFromFormat('m-d-Y', $endDate);

    if (!$start || !$end || $start > $end || $targetMonth < 1 || $targetMonth > 12) {
        return 0;
    }

    $year = $start->format('Y');

    // Define the first and last day of the target month
    $monthStart = DateTime::createFromFormat('Y-m-d', "$year-" . str_pad($targetMonth, 2, '0', STR_PAD_LEFT) . "-01");
    $monthEnd = clone $monthStart;
    $monthEnd->modify('last day of this month');

    // Check if the entire month is within the range
    if ($monthStart >= $start && $monthEnd <= $end) {
        return (int)$monthEnd->format('j'); // number of days in the month
    }

    // Otherwise, no full month coverage
    return 0;
}
}




if( !function_exists('component') ){
    function component(string $path, $add__inc_suffix = true)
    {
        $fullPath = sprintf(__DIR__ . "/../components/%s", trim($path, '/') . ($add__inc_suffix? '.inc' : '') . '.php');

        if (file_exists($fullPath)) {
            require_once $fullPath;
        } else {
            throw new RuntimeException("Component file not found: " . $fullPath);
        }
    }
}

if( !function_exists('session') ){
    function session(array|string $key, string $default = ''){
        if( !session_id() ){
            session_start();
        }

        if( is_array($key) ){
            foreach( $key as $k => $v ){
                $_SESSION[$k] = $v;
            }

            return;
        }


        return  !empty($_SESSION[$key])? $_SESSION[$key] : $default;
    }
}

if( !function_exists('current_url') ){
    function current_url($include_query = false){
        return 'http://' . $_SERVER['HTTP_HOST'] . rtrim($_SERVER['REQUEST_URI'], '/');
    }
}

if( !function_exists('is_url_same') ){
    function is_url_same($url){
        return current_url() == $url;
    }
}

if( !function_exists('saveFile') ){
    /**
     * @throws Exception
     */
    function saveFile($key, $path){
        if (isset($_FILES[$key]) && $_FILES[$key]['error'] == 0) {
            $file = $_FILES[$key];

            $uploadDir = 'planning'; // Make sure this directory exists and is writable
            $uploadDir = storage_path($uploadDir);
            $uploadFile = $uploadDir . '/' .  basename($file['name']);

            // Optional: check file type or size here

            if (move_uploaded_file($file['tmp_name'], $uploadFile)) {
                echo "File uploaded successfully to: " . $uploadFile;
            } else {
                echo "Error saving the file.";
            }
        } else {
            echo "No file uploaded or there was an error.";
        }
    }
}

if( !function_exists('daysBetweenDates') ){
    function daysBetweenDates($startDate, $endDate) {
        // Convert to DateTime objects
        $start = new DateTime($startDate);
        $end = new DateTime($endDate);

        // Calculate the difference
        $diff = $start->diff($end);

        // Return the difference in days (always positive)
        return $diff->days;
    }

}

if( !function_exists('get_all_planifications') ){
    function get_all_planifications($include_null = true){
        $db = load_db();
        $sql = "SELECT * FROM `planifications` " . (!$include_null ? " WHERE `accepted_from_employee` IS NOT NULL" : "");
        $stmt = $db->prepare($sql);
        $stmt->execute();

        $planifications = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($planifications as &$plan){
            $plan['employee'] = fetch_user_information($plan['employee_id']);
        }
        return $planifications;
    }
}

if( !function_exists('delete_planification') ){
    function delete_planification($id): array
    {
        $pdo = load_db();

        $sql = "DELETE FROM `planifications` WHERE `id` = ?";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([$id]);

        return ['success' => true];
    }
}

if( !function_exists('confirm_planifications') ){
    function confirm_planifications(){
        $pdo = load_db();

        $sql = "UPDATE `planifications` SET `accepted_from_employee` = ? WHERE `accepted_from_employee` IS NULL";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([session('user_id')]);

        return ['success' => true];
    }
}