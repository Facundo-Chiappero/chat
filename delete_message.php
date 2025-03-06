<?php
include "db.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST" || !isset($_POST['id'])) exit;

$idmsg = $_POST['id'];

if (!is_numeric($idmsg)) exit;

$sql = "DELETE FROM messages WHERE id = $idmsg";
if ($conn->query($sql) === TRUE) {
    echo "Mensaje eliminado correctamente";
} else {
    echo "Error al eliminar el mensaje: " . $conn->error;
}
?>
