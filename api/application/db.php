<?php
try {
    $connection = new PDO("mysql:host=localhost;dbname=soluide", "soluide", "KZJ6sXdxfKfUCGJb");
} catch (PDOException $e) {
    die("ERROR: " . $e->getMessage());
}
$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>
