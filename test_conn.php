<?php
require_once 'Connection.php';
try {
    $conn = new Connection();
    echo "Connection successful!";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
