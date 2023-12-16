<?php
include('connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $documentName = $_POST['document_name'];
    $destinationOfficeId = $_POST['destination_office'];

    // Insert into Document table
    $insertDocumentSql = "INSERT INTO Document (document_name, destination_office_id) VALUES (?, ?)";
    $stmtDocument = $conn->prepare($insertDocumentSql);
    $stmtDocument->bind_param("si", $documentName, $destinationOfficeId);

    // Insert into Tracking table
    $insertTrackingSql = "INSERT INTO Tracking (document_id, office_id) VALUES (LAST_INSERT_ID(), ?)";
    $stmtTracking = $conn->prepare($insertTrackingSql);
    $stmtTracking->bind_param("i", $destinationOfficeId);

    // Perform transactions
    $conn->begin_transaction();

    try {
        // Insert into Document table
        $stmtDocument->execute();

        // Insert into Tracking table
        $stmtTracking->execute();

        // Commit the transaction
        $conn->commit();

        echo "Document and Tracking Number added successfully!";
    } catch (Exception $e) {
        // Rollback the transaction in case of an error
        $conn->rollback();
        echo "Error adding document and tracking number: " . $e->getMessage();
    }

    // Close the statements
    $stmtDocument->close();
    $stmtTracking->close();
}

$conn->close();
?>

