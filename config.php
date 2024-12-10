<?php
$db_host = "localhost";
$db_name = "refeitorio";
$db_user = "root";
$db_pass = "41418162218";

$conn = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

