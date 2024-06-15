<?php
$servername = "localhost"; 
$username = "id22175132_root";
$password = "Patel@2001"; 
$database = "id22175132_localhost"; 

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $customerName = $_POST["uname"];
    $customerPhoneNumber = $_POST["phone"];
    $customerRate = $_POST["rating"];
    $customerSuggestions = $_POST["msg"];

    // Prepare and bind the SQL statement
    $stmt = $conn->prepare("INSERT INTO vishallfeedback (customerName, customerPhoneNumber, customerRate, customerSuggestions) VALUES (?, ?, ?, ?)");
    
    // Check if the phone number is valid
    // You might want to perform additional validation here, such as ensuring it contains only digits
    if (!preg_match('/^\d{10}$/', $customerPhoneNumber)) {
        die("Invalid phone number please enter proper number.");
    }
    
    $stmt->bind_param("ssss", $customerName, $customerPhoneNumber, $customerRate, $customerSuggestions);

    // Execute the statement
    if ($stmt->execute()) {
        // Redirect to index.php with success status
        header("Location: index.html?status=success");
        exit();
    } else {
        // If execution fails, redirect back to the form with an error message
        header("Location: feedback_form.html?status=error");
        exit();
    }
}

// Close statement
$stmt->close();

// Close connection
$conn->close();
?>
