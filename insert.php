<?php
session_start();

include "db.php";

if ($_SERVER["REQUEST_METHOD"] != "POST") return;

if (!isset($_POST['input']) || empty($_POST['input'])) return;

$inputText = $_POST['input'];
$userId = $_SESSION['userId'];

$sql = "INSERT INTO messages (userId, msg) VALUES (?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param('is', $userId, $inputText);

if ($stmt->execute()) {
    echo "Mensaje enviado correctamente.";
} else {
    echo "Error: " . $conn->error;
}
?>
