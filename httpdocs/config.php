<?php
// enter the config details to your database
try {
$db = new PDO('mysql:host=localhost;dbname=gamerholic7312013', 'gamerholic731201', 'vAwwc~6/3uA68pKzM"b^=QEBgCc8>L/x');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
echo 'Connection failed: ' . $e->getMessage();
}

?>