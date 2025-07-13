<?php
header('Content-Type: application/json'); // Set header to return JSON response

// DB config (same as submit.php and index.php)
$servername = "localhost";
$username = "root";
$dbname = "mydatabase";
$password = "";

// Connect to DB
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => "Connection failed: " . $conn->connect_error]);
    exit();
}

// Check if ID is provided via POST
if (isset($_POST['id'])) {
    $id = $_POST['id'];

    // Get current status
    $stmt = $conn->prepare("SELECT Status FROM info WHERE ID = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $currentStatus = $row['Status'];
        $newStatus = ($currentStatus == 0) ? 1 : 0; // Toggle status (0 to 1, or 1 to 0)

        // Update status in the database
        $updateStmt = $conn->prepare("UPDATE info SET Status = ? WHERE ID = ?");
        $updateStmt->bind_param("ii", $newStatus, $id);

        if ($updateStmt->execute()) {
            echo json_encode(['success' => true, 'newStatus' => $newStatus, 'message' => 'Status updated successfully.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error updating status: ' . $updateStmt->error]);
        }
        $updateStmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Record not found for ID: ' . $id]);
    }
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'No ID provided.']);
}

$conn->close();
?>
