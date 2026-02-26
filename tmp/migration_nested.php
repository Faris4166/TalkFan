<?php
require_once '../config/db.php';

$sql = "ALTER TABLE comments ADD COLUMN parent_id INT DEFAULT NULL, ADD FOREIGN KEY (parent_id) REFERENCES comments(id) ON DELETE CASCADE";

if ($conn->query($sql)) {
    echo "Successfully updated comments table for nested replies.";
} else {
    echo "Error updating table: " . $conn->error;
}
?>