<?php
$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "feedback"; 

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT id, customerName, customerPhoneNumber, customerRate, customerSuggestions FROM vishallfeedback";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Output CSV header
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="feedback.csv"');

    // Create a file pointer connected to the output stream
    $output = fopen('php://output', 'w');

    // Output CSV column headings
    fputcsv($output, array('ID', 'Name', 'Phone Number', 'Rate', 'Suggestions'));

    // Output data of each row
    while ($row = $result->fetch_assoc()) {
        // Output the row to CSV
        fputcsv($output, $row);
    }

    // Close the file pointer
    fclose($output);
} else {
    echo "No records found";
}

$conn->close();
?>
