<?php 


if( ! function_exists('get_user_demands') ){
    function get_user_demands(string $user_id){
        $db = load_db();

        $sql = "SELECT * FROM `demands` WHERE `employee_id` = ?";

        $stmt = $db->prepare($sql);


        try{
            $stmt->execute([$user_id]);
        }catch(PDOException $e){
            die($e->getMessage());
        }

        if( $stmt->rowCount() < 1 ){
            return [];
        }

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

if( ! function_exists('get_users_demands') ){
    function get_users_demands(){
        $db = load_db();

        $sql = "SELECT * FROM `demands` WHERE `status`=?";

        $stmt = $db->prepare($sql);

        try{
            $stmt->execute(['waiting']);
        }catch(PDOException $e){
            die($e->getMessage());
        }

        if( $stmt->rowCount() < 1 ){
            return [];
        }

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

if( ! function_exists('change_status') ) {
    function change_status($demand_id, $status){
        $db = load_db();

        $sql = "UPDATE `demands` SET `status`=? WHERE `id` = ?";

        $stmt = $db->prepare($sql);

        try{

            $stmt->execute([$status, $demand_id]);

        }catch(PDOException $err){

            die($err->getMessage());
            
        }
    }
}

if(! function_exists(('get_notifications'))){
    function get_notifications($user_id){
        $db = load_db();

        $sql = "SELECT * FROM `notifications` WHERE `employee_id` = ?";

        $stmt = $db->prepare($sql);

        try{
            $stmt->execute([$user_id]);
        }catch(PDOException $e){
            die($e->getMessage());
        }

        if( $stmt->rowCount() < 1 ){
            return [];
        }

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}


if( !function_exists('fetch_creation_demands') ){
    function fetch_creation_demands($compte_valid='waiting'){
        $db = load_db();

        $sql = "SELECT * FROM `employees` WHERE `compte_valid` = ?";

        $stmt = $db->prepare($sql);

        try{
            $stmt->execute([$compte_valid]);
        }catch(PDOException $e){
            die($e->getMessage());
        }

        if( $stmt->rowCount() < 1 ){
            return [];
        }

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}


if(!function_exists('handleAccount')){
    function handleAccount($type, $id){
        $t = $type == 'activate' ? 1 : 0;

        $db = load_db();

        $sql = "UPDATE `employees` SET `compte_valid` = ? WHERE `id` = ?";

        $stmt = $db->prepare($sql);

        $stmt->execute([$t, $id]);
    }
}