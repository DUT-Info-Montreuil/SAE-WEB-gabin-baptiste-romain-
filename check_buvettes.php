<?php
require_once 'Connection.php';
$con = new Connection();
$stmt = Connection::$db->query("SELECT * FROM Buvette");
print_r($stmt->fetchAll());
?>