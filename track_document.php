<?php
include('connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $documentId = $_POST['document_id'];

    $sql = "SELECT t.tracking_id, d.document_name, d.date_created, o.office_name, o.office_location
            FROM Tracking t
            JOIN Document d ON t.document_id = d.document_id
            JOIN Office o ON t.office_id = o.office_id
            WHERE t.document_id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $documentId);

    if ($stmt->execute()) {
        $result = $stmt->get_result();

        echo "<h2>Tracking Result</h2>";

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<p><strong>Tracking ID:</strong> " . $row['tracking_id'] . "</p>";
                echo "<p><strong>Document Name:</strong> " . $row['document_name'] . "</p>";
                echo "<p><strong>Date Created:</strong> " . $row['date_created'] . "</p>";
                echo "<p><strong>Current Office:</strong> " . $row['office_name'] . "</p>";
                echo "<p><strong>Office Location:</strong> " . $row['office_location'] . "</p>";
            }
        } else {
            echo "<p>No tracking information found for the specified document ID.</p>";
        }
    } else {
        echo "Error tracking document: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
