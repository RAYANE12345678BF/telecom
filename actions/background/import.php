<?php
if(! session_id()){
    session_start(); // initialisation ta3 session bah ncontroliw les donnes entre diff page
}


const STORE_ALL = true;

require __DIR__ . '/../../vendor/autoload.php'; // autoload for autoload function from helper files



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
            $matricule_col = 'Emp No.'; // Replace with actual column header name
            $targetColumnIndex = array_search($matricule_col, $headerValues) + 1; // 1-based index
            $nameColIndex = array_search('Name', $headerValues) + 1;

            // Iterate through the rows and modify the target column
            $iterator = $sheet->getRowIterator(3); // Start from row 2 to skip header
            $i = 0;

            $users = [];
            foreach ($iterator as $row) {
                try {
                    $matriculeCell = $row->getCellIterator(convertFromAscii($targetColumnIndex));
                    $nameCell = $row->getCellIterator(convertFromAscii($nameColIndex));

                    $matricule = $matriculeCell->current()->getValue();

                    if (isset($users[$matricule])) {
                        continue;
                    }

                    $name = explode(' ', $nameCell->current()->getValue());

                    $users[$matricule] = [
                        'matricule' => $matricule,
                        'nom' => $name[0],
                        'prenom' => $name[1],
                        'email_professionnel' => $matricule . '@telecom.ma',
                        'password' => 'password123',
                        'role_id' => rand(1, 6),
                        'service_id' => rand(1, 15),
                        'departement_id' => rand(1, 4),
                        'compte_valid' => 1,
                    ];

                } catch (Exception $e) {
                    // Handle exception if the cell is not found or any other issue
                    echo 'Error processing row: ' . $e->getMessage();
                }
            }


            insertMultipleRows('employees', array_values($users));
        }catch(\Exception $e){
            echo $e->getMessage();
        }
    } else {
        echo 'Error uploading file. Error code: ' . $uploadedFile['error'];
    }
} else {
    echo 'No file uploaded.';
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>File Upload Form</title>
</head>
<body>

<h2>Upload a File</h2>
<form action="./import.php" method="POST" enctype="multipart/form-data">
    <label for="file">Choose a file:</label><br>
    <input type="file" id="file" name="file" required><br><br>
    <input type="submit" value="Upload">
</form>

</body>
</html>

