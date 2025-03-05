<?php

if (! function_exists('login')){
    function login($email, $password) : array{
        $sql = "SELECT * FROM `employees` WHERE email_professionnel = ?";

        $db = load_db();

        $stmt = $db->prepare($sql);

        $stmt->execute([$email]);

        if( $stmt->rowCount() < 1 ){
            return [
                'error' => 'User with that email does not exist'
            ];
        }

        $sql = "SELECT * FROM `employees` WHERE email_professionnel = ? AND password = ?";

        $stmt = $db->prepare($sql);

        $stmt->execute([$email, $password]);

        if( $stmt->rowCount() < 1 ){
            return [
                'error' => 'wrong password'
            ];
        }
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if( $user['compte_valid'] != 1 ){
            return [
                'error' => 'Account is not activated'
            ];
        }
        
        unset($user['password']);

        return $user;
    }

}

if( ! function_exists('is_email_exists') ){
    function is_email_exists($email) : bool{
        $sql = "SELECT * FROM employees WHERE email_professionnel = ?";
        $db = load_db();
        $stmt = $db->prepare($sql);
        $stmt->execute([$email]);
        if( $stmt->rowCount() > 0 ){
            return true;
        }
        return false;
    }
}

if(! function_exists('register')){
    function register($prenom, $nom, $email, $password, $department, $service, $enterprise_position) : array{
        $db = load_db();

         if( is_email_exists($email) ){
             return [
                 'error' => 'Email already exists'
             ];
         }

        $sql = "INSERT INTO employees (prenom, nom, email_professionnel, password, departement_id, service_id, role_id) VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = $db->prepare($sql);

        try{
            $stmt->execute([$prenom, $nom, $email, $password, $department, $service, $enterprise_position]);
        }catch (PDOException $e){
            echo implode("-",[$prenom, $nom, $email, $password, $department, $service, $enterprise_position]);

            return [
                'error' => 'unknown error : ' . $e->getMessage(),
            ];
        }

        return [
            'user_id' => $db->lastInsertId(),
            'success' => 'User has been registered',
        ];
    }
}

if( !function_exists('save_user') ){
    function save_user($user) : array{
        if( !session_id() ){
            session_start();
        }

        $_SESSION['user'] = $user;
        $_SESSION['user_id'] = $user['id'];
        return $_SESSION['user'];
    }
}

if( !function_exists('get_user') ){
    function get_user($id){
        $db = load_db();
        $sql = "SELECT * FROM employees WHERE id = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$id]);
        if( $stmt->rowCount() > 0 ){
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            unset($user['password']);
            return $user;
        }
        return null;
    }
}

if( !function_exists('get_all_users') ){
    function get_all_users(): array
    {
        $db = load_db();

        $sql = "SELECT * FROM employees";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        if( $stmt->rowCount() > 0 ){
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach( $users as $user ){
                unset($user['password']);
            }

            return $users;
        }

        return [];
    }
}

if( ! function_exists('redirect_if_auth') ){
    function redirect_if_auth(){
        if( !session_id() ){
            session_start();
        }

        if( isset($_SESSION['user_id']) && isset($_SESSION['user']) ){
            redirect(url('dashboard/'));
        }
    }
}