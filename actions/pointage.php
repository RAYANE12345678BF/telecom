<?php
// Include the necessary PhpSpreadsheet files
require __DIR__ . '/../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Calculation\DateTimeExcel\Date;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Exception;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['file'])) {

    $uploadedFile = $_FILES['file'];

    // Check if file is valid
    if ($uploadedFile['error'] === UPLOAD_ERR_OK) {
        $filePath = $uploadedFile['tmp_name'];

        try {

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

                    // convert date from d/m/y to y-m-d
                    $dateValue = DateTime::createFromFormat('d/m/Y', $dateCell->current()->getValue());
                    $formattedDate = $dateValue->format('Y-m-d');

                    // get user demands
                    if (isset($users[$user_no])) {
                        $user = $users[$user_no];
                    } else {
                        $user = getUserWithDemands($user_no, 'accepted');
                        if( !$user ){
                            $cell->current()->setValue('user not found');
                            continue;
                        }
                        $users[$user_no] = $user;
                    }

                    // Check if formatted date between startdate and enddate
                    if ($user) {
                        $active_demands = array_filter($user['demands'], function ($demand) use ($formattedDate) {
                            return $demand['date_debut'] <= $formattedDate && $demand['date_fin'] >= $formattedDate;
                        });

                        if (count($active_demands) > 0) {
                            // Set the cell value to 'OK' if user found and date is between start and end date
                            $cell->current()->setValue($active_demands[0]['type']);
                        } else {
                            // Set the cell value to 'NOK' if user found but date is not between start and end date
                            $cell->current()->setValue('NOK');
                        }
                    } else {
                        // Set the cell value to 'NOK' if user not found
                        $cell->current()->setValue('NOK');
                    }
                } catch (Exception $e) {
                    // Handle exception if the cell is not found or any other issue
                    echo 'Error processing row: ' . $e->getMessage();
                }
            }


            // Save the modified file
            $outputFilePath = 'modified_' . $uploadedFile['name'];
            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->save($outputFilePath);

            $file_name = $outputFilePath;
            $file_url =  url('actions/' . $file_name);


            echo "File uploaded and processed successfully! <a href='{$outputFilePath}'>Download the modified file</a>";
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
    } else {
        echo 'Error uploading file. Error code: ' . $uploadedFile['error'];
    }
} else {
    echo 'No file uploaded.';
}
