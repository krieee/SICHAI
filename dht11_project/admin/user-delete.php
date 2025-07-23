<?php
include __DIR__ . '/../database.php';
$id = $_GET['id'];

$query="DELETE FROM users WHERE id = $id";
$query_run = mysqli_query($mysqli,$query);

header('Location: users.php');

?>