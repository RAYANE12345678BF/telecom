<?php
if(! session_id()){
    session_start();
}

require __DIR__ . '/../vendor/autoload.php';



use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Exception;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['file'])) {

    $pdo = load_db();

    $uploadedFile = $_FILES['file'];
    $fileName = $uploadedFile['name'];

    $admin_id = $_SESSION['user_id'] ?? $_SESSION['user']['id'];
    $rows = [];
    // Check if file is valid
    if ($uploadedFile['error'] === UPLOAD_ERR_OK) {
        $filePath = $uploadedFile['tmp_name'];

        try {
            $path = 'storage/pointage';
            $storageDir = __DIR__ . '/../' . $path;
            if (!file_exists($storageDir)) {
                mkdir($storageDir, 0777, true);
            }


            $users = [];
            // Load the spreadsheet
            $spreadsheet = IOFactory::load($filePath);
            $sheet = $spreadsheet->getActiveSheet();


            // Let's assume the header is in the first row
            $header = $sheet->getRowIterator(2)->current()->getCellIterator();
            $headerValues = [];
            foreach ($header as $h_cell) {
                $headerValues[] = $h_cell->getValue();
            }


            // Define the column index you want to modify based on header
            $targetColumn = 'Exception'; // Replace with actual column header name
            $targetColumnIndex = array_search($targetColumn, $headerValues) + 1; // 1-based index
            $dateColumnIndex = array_search('Date', $headerValues) + 1;
            $daypartColIndex = array_search('Timetable', $headerValues) + 1;

            $timestamp = date('Y-m-d_H-i-s');
            $newFileName = "pointage_{$timestamp}.xlsx";
            $newFilePath = $storageDir . '/' . $newFileName;



            if ($targetColumnIndex === false) {
                die("Column with header '{$targetColumn}' not found.");
            }
            // Iterate through the rows and modify the target column
            $iterator = $sheet->getRowIterator(3); // Start from row 2 to skip header
            $i = 0;
            foreach ($iterator as $row) {
                try {
                    $cell = $row->getCellIterator(convertFromAscii($targetColumnIndex));
                    $user_no = $row->getCellIterator()->current()->getValue();
                    $dateCell = $row->getCellIterator(convertFromAscii($dateColumnIndex));
                    $daypartCell = $row->getCellIterator(convertFromAscii($daypartColIndex));

                    // convert date from d/m/y to y-m-d
                    $dateValue = DateTime::createFromFormat('d/m/Y', $dateCell->current()->getValue());
                    $formattedDate = $dateValue->format('Y-m-d');

                    $dayPartValue = $daypartCell->current()->getValue();

                    // get user demands
                    if (isset($users[$user_no])) {
                        $user = $users[$user_no];
                    } else {
                        $user = getUserWithDemands($user_no, 'accepted');
                        if( !$user ){
                            $cell->current()->setValue('no justifié');
                            continue;
                        }
                        $users[$user_no] = $user;
                    }

                    // Check if formatted date between startdate and enddate
                    if ($user) {
                        $row = [
                            'employee_matricule' => $user_no,
                            'date' => $formattedDate,
                            'day_part' => $dayPartValue == 'matiniée' ? 'morning' : 'evening',
                        ];

                        $active_demands = array_filter($user['demands'], function ($demand) use ($formattedDate) {
                            return $demand['date_debut'] <= $formattedDate && $demand['date_fin'] >= $formattedDate;
                        });

                        if (count($active_demands) > 0) {
                            // Set the cell value to 'OK' if user found and date is between start and end date
                            $cell->current()->setValue($active_demands[0]['type']);
                            $row['justify'] = $active_demands[0]['type'];
                        } else {
                            // Set the cell value to 'NOK' if user found but date is not between start and end date
                            $cell->current()->setValue('no justifié');
                            $row['justify'] = 'no';
                        }
                    } else {
                        // Set the cell value to 'NOK' if user not found
                        $cell->current()->setValue('no justifié');
                        $row['justify'] = 'no';
                    }

                    $rows[] = $row;
                } catch (Exception $e) {
                    // Handle exception if the cell is not found or any other issue
                    echo 'Error processing row: ' . $e->getMessage();
                }
            }


            insertMultipleRows('absenses', $rows);
            // Save the modified file
            $outputFilePath = 'files/' . 'modified_' . $uploadedFile['name'];
            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->save(storage_path($outputFilePath));

            $file_name = $outputFilePath;
            $file_url =  url('actions/' . $file_name);

            $uploadDate = date('Y-m-d');
            $stmt = $pdo->prepare("INSERT INTO appointment_files (admin_id, original_name, file_name, file_path)
                                   VALUES (?, ?, ?, ?)");
            $stmt->execute([$admin_id, $fileName, $newFileName, $outputFilePath]);

            print_r(json_encode([
                'success' => true,
                'message' => 'file processed successfully',
                'file_url' => storage_path($outputFilePath, true)
            ]));
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
    } else {
        echo 'Error uploading file. Error code: ' . $uploadedFile['error'];
    }
} else {
    echo 'No file uploaded.';
}