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

        $sql = "SELECT * FROM `demands`";

        $stmt = $db->prepare($sql);

        try{
            $stmt->execute();
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