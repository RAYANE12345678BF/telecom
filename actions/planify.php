<?php

require_once __DIR__ . '/../vendor/autoload.php';

if( $_SERVER['REQUEST_METHOD'] === 'POST' ) {

    if( isset($_POST['action']) && $_POST['action'] == 'delete' ){
        $result = delete_planification($_POST['id']);

        if( $result['success'] ){
            send_json_response([
                'success' => true,
                'message' => 'success'
            ]);
        }else{
            send_json_response([
                'success' => false,
                'message' => $result['message']
            ]);
        }

    }

    if( isset($_POST['action']) && $_POST['action'] == 'confirm' ){
        $result = confirm_planifications();

        if( $result['success'] ){
            send_json_response([
                'success' => true,
                'message' => 'success'
            ]);
        }else{
            send_json_response([
                'success' => false,
                'message' => $result['message']
            ]);
        }

    }

    if( !session_id() ){
        session_start();
    }

    // Ensure user is authenticated
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        echo json_encode(['error' => 'User not authenticated.']);
        exit;
    }

    // Get user input from $_POST
    $inputData = $_POST;

    // Validate the data using the validate() helper function
    try {
        $validatedData = validate($inputData, [
            'leaveType' => 'required',
            'startDate' => 'required',
            'endDate' => 'required',
            'motif' => 'required',
            'contact' => ['required', 'phone'],
        ]);
    } catch (Exception $e) {
        http_response_code(400);
        echo json_encode(['error' => 'Validation failed: ' . $e->getMessage()]);
        exit;
    }

    $pdo = load_db();

    // Prepare the SQL query to insert data into the "planifications" table
    $sql = "INSERT INTO planifications (
                            employee_id,
                            conge_type,
                            motif,
                            destination, 
                            contact,
                            start_date,
                            end_date,
                            note
                            ) VALUES (?,?,?,?,?,?,?,?)";
    $stmt = $pdo->prepare($sql);

    // Execute the query with the validated data
    try {
        $stmt->execute([
            $_SESSION['user_id'],
            'conge_annual',
            $inputData['motif'],
            $inputData['destination'] ?? null,
            $inputData['contact'],
            $inputData['startDate'],
            $inputData['endDate'],
            $inputData['note'] ?? null
        ]);

        $_SESSION['status'] = 'success';

        echo json_encode([
            'success' => true,
            'message' => 'Data inserted successfully.',
            'redirect_url' => dashboard_url('/')
        ]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Failed to insert data: ' . $e->getMessage()]);
    }
}