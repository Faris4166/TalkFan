<?php
require_once '../config/db.php';

$sql = "ALTER TABLE users ADD COLUMN last_active TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP";

if ($conn->query($sql)) {
    echo "Successfully added last_active column.";
} else {
    echo "Error adding column: " . $conn->error;
}
?>