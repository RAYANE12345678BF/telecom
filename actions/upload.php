<?php
if(! session_id()){
    session_start(); // initialisation ta3 session bah ncontroliw les donnes entre diff page
}


const STORE_ALL = true;

require __DIR__ . '/../vendor/autoload.php'; // autoload for autoload function from helper files



use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Exception; // EXCEL Package, through composer autoloading

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


            // Let's assume the header is in the second row
            $header = $sheet->getRowIterator(2)->current()->getCellIterator();
            $headerValues = [];
            foreach ($header as $h_cell) {
                $headerValues[] = $h_cell->getValue(); // nhoto les valeur tal header fi tableau
            }


            // Define the column index you want to modify based on header
            $targetColumn = 'Exception'; // Replace with actual column header name
            $targetColumnIndex = array_search($targetColumn, $headerValues) + 1; // 1-based index
            $dateColumnIndex = array_search('Date', $headerValues) + 1;
            $daypartColIndex = array_search('Timetable', $headerValues) + 1;
            $isAbsentColIndex = array_search('Absent', $headerValues) + 1;

            $onDutyColIndex = array_search('On duty', $headerValues) + 1;
            $offDutyColIndex = array_search('Off duty', $headerValues) + 1;

            $clockInColIndex = array_search('Clock In', $headerValues) + 1;
            $clockOffColIndex = array_search('Clock Out', $headerValues) + 1;

            $lateThreshold = 59;

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

                    $user = $users[$user_no] ?? getUserWithDemands($user_no, 'accepted');

                    if (!$user){
                        $users[$user_no] = null;
                        continue;
                    }


                    $dateCell = $row->getCellIterator(convertFromAscii($dateColumnIndex));
                    $daypartCell = $row->getCellIterator(convertFromAscii($daypartColIndex));
                    $isAbsentCell = $row->getCellIterator(convertFromAscii($isAbsentColIndex));
                    $onDutyCell = $row->getCellIterator(convertFromAscii($onDutyColIndex));
                    $offDutyCell = $row->getCellIterator(convertFromAscii($offDutyColIndex));
                    $clockInCell = $row->getCellIterator(convertFromAscii($clockInColIndex));
                    $clockOffCell = $row->getCellIterator(convertFromAscii($clockOffColIndex));

                    // convert date from d/m/y to y-m-d
                    $dateValue = DateTime::createFromFormat('d/m/Y', $dateCell->current()->getValue());
                    $formattedDate = $dateValue->format('Y-m-d');

                    $dayPartValue = $daypartCell->current()->getValue();

                    $isAbsent = $isAbsentCell->current()->getValue() == "True";
                    $onDuty = $onDutyCell->current()->getValue();
                    $offDuty = $offDutyCell->current()->getValue();
                    $clockIn = $clockInCell->current()->getValue();
                    $clockOff = $clockOffCell->current()->getValue();
                    $isLate = false;
                    $row = [
                        'employee_matricule' => $user_no,
                        'date' => $formattedDate,
                        'day_part' => $dayPartValue == 'matiniée' ? 'morning' : 'evening',
                        'is_absent' => $isAbsent ? '1' : '0',
                        'on_duty' => $onDuty,
                        'off_duty' => $offDuty,
                        'clock_in' => empty($clockIn) ? null : $clockIn,
                        'clock_out' => empty($clockOff) ? null : $clockOff,
                        'justification' => null,
                        'late_hours' => null,
                    ];

                    if( !$isAbsent ){
                        // he is not absence, but can be late !

                        $on_late = time_diff($clockIn, $onDuty); // retard ta3 da5la
                        $off_late = time_diff($offDuty, $clockOff ?? $offDuty); // retard ta3 5arja

                        $hours_sum = 0;

                        $lates = [$on_late, $off_late];

                        foreach( $lates as $l ){
                            $times = -1;
                            if( $l > $lateThreshold ){
                                $isLate = true;
                                $times = (int) ($l / $lateThreshold);
                            }

                            $hours_sum += max($times, 0);
                        }

                        $row['late_hours'] = $hours_sum;

                        $cell->current()->setValue('');
                    } else {
                        $justification = isAbsentJustified($user, $formattedDate, is_array($user));

                        if( $justification === false ){
                            continue;
                        }

                        if( !$justification ){
                            // l'employee mapointach w ma3andoch justification, donc ndiroh ghab swaue3 kol

                            $hours = time_diff($offDuty, $onDuty);

                            $row['late_hours'] = max((int)($hours / $lateThreshold), 0);
                        }

                        $row['justification'] = $justification;
                        $cell->current()->setValue($justification ?? 'non justifié');

                    }
                    $rows[] = $row;
                } catch (Exception $e) {
                    // Handle exception if the cell is not found or any other issue
                    echo 'Error processing row: ' . $e->getMessage();
                }
            }


            insertMultipleRows('appointments', $rows);
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