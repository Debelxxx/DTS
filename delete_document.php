<?php
include('connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $documentId = $_POST['document_id'];

    // Delete from Tracking table
    $deleteTrackingSql = "DELETE FROM Tracking WHERE document_id = ?";
    $stmtTracking = $conn->prepare($deleteTrackingSql);
    $stmtTracking->bind_param("i", $documentId);

    // Delete from Document table
    $deleteDocumentSql = "DELETE FROM Document WHERE document_id = ?";
    $stmtDocument = $conn->prepare($deleteDocumentSql);
    $stmtDocument->bind_param("i", $documentId);

    // Perform transactions
    $conn->begin_transaction();

    try {
        // Delete from Tracking table
        $stmtTracking->execute();

        // Delete from Document table
        $stmtDocument->execute();

        // Commit the transaction
        $conn->commit();

        echo "Document and Tracking Number deleted successfully!";
    } catch (Exception $e) {
        // Rollback the transaction in case of an error
        $conn->rollback();
        echo "Error deleting document and tracking number: " . $e->getMessage();
    }

    // Close the statements
    $stmtTracking->close();
    $stmtDocument->close();
}

$conn->close();
?>
