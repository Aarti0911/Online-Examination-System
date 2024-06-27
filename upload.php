<?php

// MySQL configuration
$mysql_host = "locahost";
$mysql_user = "root";
$mysql_password = "null";
$mysql_database = "quiz_db";

// Connect to MySQL
$conn = new mysqli(localhost , root,null , quiz_db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_FILES['file']['error'] == UPLOAD_ERR_OK && $_FILES['file']['tmp_name'] != '') {
    $file = $_FILES['file']['tmp_name'];

    // Read Excel file into a DataFrame
    require 'vendor/autoload.php'; // Include PHPExcel library
    $reader = new PhpOffice\PhpSpreadsheet\Reader\Xlsx();
    $spreadsheet = $reader->load($file);
    $worksheet = $spreadsheet->getActiveSheet();
    $data = $worksheet->toArray(null, true, true, true);

    // Insert data into MySQL
    foreach ($data as $row) {
        $name = $row['A'];
        $roll_number = $row['B'];
        $email = $row['C'];
        $score = $row['D'];

        $sql = "INSERT INTO student_data (name, roll_number, email, score) VALUES ('$name', '$roll_number', '$email', '$score')";
        if ($conn->query($sql) !== TRUE) {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    echo "File uploaded and student data inserted into MySQL successfully.";

} else {
    echo "Error uploading file.";
}

$conn->close();

?>