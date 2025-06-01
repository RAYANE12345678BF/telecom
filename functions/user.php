<?php



if (! function_exists('get_user_demands')) {
    function get_user_demands(string $user_id)
    {
        $db = load_db();

        $sql = "SELECT `id` FROM `demands` WHERE `employee_id` = ?";

        $stmt = $db->prepare($sql);


        try {
            $stmt->execute([$user_id]);
        } catch (PDOException $e) {
            die($e->getMessage());
        }

        if ($stmt->rowCount() < 1) {
            return [];
        }

        $demands = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $demands_w = [];

        foreach($demands as $demand){
            $demands_w[] = get_demand_with_lifecycle($demand['id']);
        }

        return $demands_w;
    }
}

if (! function_exists('get_users_demands')) {
    function get_users_demands($status = 'waiting')
    {
        $db = load_db();

        $sql = "SELECT * FROM `demands` WHERE `status`=?";

        $stmt = $db->prepare($sql);

        try {
            $stmt->execute([$status]);
        } catch (PDOException $e) {
            die($e->getMessage());
        }

        if ($stmt->rowCount() < 1) {
            return [];
        }

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

if (! function_exists('change_status')) {
    function change_status($demand_id, $status)
    {
        $db = load_db();

        $sql = "UPDATE `demands` SET `status`=? WHERE `id` = ?";

        $stmt = $db->prepare($sql);

        try {

            $stmt->execute([$status, $demand_id]);
        } catch (PDOException $err) {

            die($err->getMessage());
        }
    }
}

if (! function_exists(('get_notifications'))) {
    function get_notifications($user_id)
    {
        $db = load_db();

        $sql = "SELECT * FROM `notifications` WHERE `employee_id` = ?";

        $stmt = $db->prepare($sql);

        try {
            $stmt->execute([$user_id]);
        } catch (PDOException $e) {
            die($e->getMessage());
        }

        if ($stmt->rowCount() < 1) {
            return [];
        }

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}


if (!function_exists('fetch_creation_demands')) {
    function fetch_creation_demands($compte_valid = 'waiting')
    {
        $db = load_db();

        $sql = "SELECT * FROM `employees` WHERE `compte_valid` = ?";

        $stmt = $db->prepare($sql);

        try {
            $stmt->execute([$compte_valid]);
        } catch (PDOException $e) {
            die($e->getMessage());
        }

        if ($stmt->rowCount() < 1) {
            return [];
        }

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}


if (!function_exists('handleAccount')) {
    function handleAccount($type, $id)
    {
        $t = $type == 'activate' ? 1 : 0;

        $db = load_db();

        $sql = "UPDATE `employees` SET `compte_valid` = ? WHERE `id` = ?";

        $stmt = $db->prepare($sql);

        $stmt->execute([$t, $id]);
    }
}

if (!function_exists('push_user_creation_notification')) {
    function push_user_creation_notification($user_id)
    {
        $db = load_db();

        $title = 'creation demand';
        $description = 'il y a une nouvelle demande de creation de compte';

        $sql = "INSERT INTO `notifications` (`employee_id`, `title`, `description`,`url` ) VALUES (?, ?, ?, ?)";

        $stmt = $db->prepare($sql);

        try {
            $stmt->execute([$user_id, $title, $description, url('dashboard/employees_requests.php')]);
        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }
}

if (!function_exists('get_grh_id')) {
    function get_grh_id(): mixed
    {
        $db = load_db();

        $sql = "SELECT `employees`.`id` FROM `employees` JOIN `roles` ON `employees`.`role_id` = `roles`.`id` WHERE `roles`.`nom` = ?";

        $stmt = $db->prepare($sql);

        try {
            $stmt->execute(['GRH']);
        } catch (PDOException $e) {
            die($e->getMessage());
        }

        if ($stmt->rowCount() < 1) {
            return null;
        }

        return $stmt->fetch(PDO::FETCH_ASSOC)['id'];
    }
}

if (!function_exists('fetch_user_information')) {
    function fetch_user_information(string $user_id, $check = true): array
    {
        if( !session_id() ){
            session_start();
        }
        $db = load_db();
        $sql = "SELECT * FROM `employees` WHERE id=?";
        $result = $db->prepare($sql);
        $result->execute([$user_id]);
        $user = $result->fetch(PDO::FETCH_ASSOC);

/* 
        if( $user['role_id'] != "5" && $check && (empty($user['matricule']) || empty($user['superior_id'])) && (!str_ends_with($_SERVER['REQUEST_URI'], "dashboard") ||!str_ends_with($_SERVER['REQUEST_URI'], "dashboard/index.php")) ){
            $_SESSION['error'] = "must define matricule and superior employee";
            redirect_back(-1);
        } */


        $user['role'] = get_role($user['role_id']);
        $user['service'] = get_service($user['service_id']);
        $user['department'] = get_department($user['departement_id']);
        $user['superior'] = empty($user['superior_id']) ? null :  get_user($user['superior_id']);
        $user['address'] = !empty($user['address_id']) ? get_address($user['user_id']) : [];
        $user['substitute'] = !empty($user['substitute_id']) ? get_user($user['substitute_id']) : null;

        return $user;
    }
}

if (!function_exists('isProfileComplete')) {
    function isProfileComplete(array $user)
    {
        $data = ['phone', 'birth_day', 'birth_place', 'etat_civil'];

        foreach ($data as $k) {
            if (empty($user[$k])) {
                return $k;
            }
        }

        return true;
    }
}

if (!function_exists('update_user')) {
    function update_user($user_id, array $data)
    {
        $db = load_db();
        $cols = array_keys($data);
        $values = array_values($data);
        $parts = [];

        for ($i = 0; $i < count($cols); $i++) {
            $parts[] = sprintf("%s=?", $cols[$i]);
        }

        $sql = sprintf("UPDATE `employees` SET %s WHERE `id`=?", implode(',', $parts));

        $stmt = $db->prepare($sql);

        $stmt->execute(array_merge($values, [$user_id]));

        return true;
    }
}

if (!function_exists('get_user_password')) {
    // Simulated function to fetch user password (replace with actual DB query)
    function get_user_password($user_id)
    {
        $db = load_db();

        $sql = "SELECT `password` FROM `employees` WHERE `id`=? ";

        $stmt = $db->prepare($sql);

        $stmt->execute([$user_id]);
        // Fetch the password hash from database (Example hash for 'oldpassword123')
        return $stmt->fetch(PDO::FETCH_ASSOC)['password'];
    }
}


if( !function_exists('change_password') ){
    function change_password($user_id, $actual_password, $new_password, $confirm_password) {
        // Fetch the stored hashed password for the user (simulate fetching from DB)
        $stored_hashed_password = get_user_password($user_id); // You should replace this function with actual DB retrieval
        
        // Verify the actual password
        if (!password_verify($actual_password, $stored_hashed_password)) {
            send_json_response(["success" => false, "message" => "Incorrect current password"]);
            return;
        }
    
        // Validate new password length
        if (strlen($new_password) < 8) {
            send_json_response(["success" => false, "message" => "New password must be at least 8 characters long"]);
            return;
        }
    
        // Check if new password matches confirmation
        if ($new_password !== $confirm_password) {
            send_json_response(["success" => false, "message" => "New password and confirmation do not match"]);
            return;
        }
    
        // Hash the new password before storing
        $hashed_new_password = password_hash($new_password, PASSWORD_BCRYPT);
    
        // Update the password using your predefined update function
        if (update_user($user_id, ['password' => $hashed_new_password])) {
            send_json_response(["success" => true, "message" => "Password updated successfully"]);
        } else {
            send_json_response(["success" => false, "message" => "Failed to update password"]);
        }
    }
}

if(! function_exists('update_profile_picture')){
    function update_profile_picture($user_id, $file){
        $db = load_db();
        $sql = "UPDATE `employees` SET `profile_photo` = ? WHERE `id` = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$file, $user_id]);
    }
}

if( !function_exists('profile_photo_url') ){
    function profile_photo_url($user_id){
        $user = fetch_user_information($user_id);
        return url('uploads/'.$user['profile_photo']);
    }
}

if( !function_exists('get_user_with') ){
    function get_user_with($column, $value){
        $db = load_db();

        $sql = sprintf("SELECT * FROM `employees` WHERE %s=?", $column);

        $stmt = $db->prepare($sql);

        try{
            $stmt->execute([$value]);
        }catch(PDOException $e){
            return null;
        }

        return $stmt->rowCount() > 0 ? $stmt->fetch(PDO::FETCH_ASSOC) : null;
    }
}


if( !function_exists('fetch_absenses') ){
    function fetch_absenses($user_id, $grouped=false) : array|null {
        $pdo = load_db();

        $user = get_user($user_id);

        $matricule = $user['matricule'];

        $sql = "SELECT * FROM `absenses` WHERE employee_matricule=?";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([$matricule]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

if( !function_exists('get_all_conges') ){
    function get_all_conges($user_id, $type, $status){
        $db = load_db();
        $sql = "SELECT * FROM `demands` WHERE type=? AND status=? AND employee_id=?";

        $stmt = $db->prepare($sql);

        $stmt->execute([$type, $status, $user_id]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}