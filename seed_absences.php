<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/functions/helpers.php';

// Function to generate a random date between two dates
function randomDate($start_date, $end_date) {
    $start_timestamp = strtotime($start_date);
    $end_timestamp = strtotime($end_date);
    $random_timestamp = rand($start_timestamp, $end_timestamp);
    return date('Y-m-d', $random_timestamp);
}

// Function to generate random absence data
function generateAbsenceData($employee_matricule) {
    $day_parts = ['morning', 'evening'];
    $justify_options = ['yes', 'no'];
    
    // Generate 5-15 random absences per employee
    $num_absences = rand(5, 15);
    $absences = [];
    
    for ($i = 0; $i < $num_absences; $i++) {
        $absences[] = [
            'date' => randomDate('2023-01-01', '2024-02-29'),
            'day_part' => $day_parts[array_rand($day_parts)],
            'justify' => $justify_options[array_rand($justify_options)],
            'employee_matricule' => $employee_matricule
        ];
    }
    
    return $absences;
}

try {
    $db = load_db();
    
    // Get all employee matricules
    $stmt = $db->query("SELECT matricule FROM employees");
    $employees = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    if (empty($employees)) {
        die("No employees found in the database.\n");
    }
    
    // Generate and insert absence data for each employee
    $total_absences = 0;
    foreach ($employees as $matricule) {
        $absence_data = generateAbsenceData($matricule);
        $total_absences += count($absence_data);
        
        // Insert the absences using the helper function
        if (insertMultipleRows('absenses', $absence_data)) {
            echo "Successfully inserted " . count($absence_data) . " absences for employee $matricule\n";
        } else {
            echo "Failed to insert absences for employee $matricule\n";
        }
    }
    
    echo "\nTotal absences inserted: $total_absences\n";
    
} catch (Exception $e) {
    die("Error: " . $e->getMessage() . "\n");
} 