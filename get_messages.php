<?php
include "db.php";

session_start();
$userId = $_SESSION['userId'];

$sql = "SELECT messages.msg, users.name, users.admin, messages.userId, messages.id FROM messages
        INNER JOIN users ON messages.userId = users.id
        ORDER BY messages.id ASC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $class = ($row['userId'] == $userId) ? 'my-message' : 'message-container';
        
        echo "<div class='$class' id='$row[id]'>";
        echo "<div>";
        echo "<div class='username'>" . htmlspecialchars($row['name']) . "</div>";
        echo "<div class='message'>" . htmlspecialchars($row['msg']) . "</div>";
        echo "</div>";

        if ($_SESSION['admin'] == 1) {
            echo "<img onclick='deleteMsg($row[id])' id='erase' src='assets/close.svg' alt='delete msg' />";
        }

        echo "</div>";
    }
    echo "<div class='chatEnd'></div>";

    echo "<button id='btnarrow-down'><img onclick='scrollBtn()' id='arrow-down' src='assets/arrow-down.svg' alt='go down'/></button>";

} else {
    echo "<div>No hay mensajes aún.</div>";
}
?>
