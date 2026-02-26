<?php
require_once '../config/db.php';

$sql = "ALTER TABLE posts ADD COLUMN status VARCHAR(20) DEFAULT 'published'";

if ($conn->query($sql)) {
    echo "Successfully added status column to posts table.";
} else {
    echo "Error adding column: " . $conn->error;
}
?>