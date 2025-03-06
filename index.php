<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
</head>
<body>

    <?php
    session_start();

    if (!isset($_SESSION['userId'])) {
        header("Location: auth.php");
        exit();
    }
    ?>

    <div class="chat-container">
        <div id="messages" class="messages">
            <?php
            include "db.php";
            include "get_messages.php";
            ?>
        </div>
        
        <div class="formsCont">
            <form id="chatForm" class="chat-form">
                <input type="text" name="input" id="input" placeholder="send a message" class="input-field"/>
                <button type="submit" class="send-button">
                    <img src="assets/send.svg" alt="send">
                </button>
            </form>

            <?php if (isset($_SESSION['userId'])) { ?>
                <form action="logout.php" method="POST">
                    <button type="submit" class="logout-button">Close session</button>
                </form>
            <?php } ?>
        </div>
    </div>



    <div class="modal">
        <h2>Do you realy want to delete this message?</h2>
        <div>
        <button id="deletebtn">DELETE</button>
        <button id="cancelbtn">CANCEL</button>
        </div>
    </div>

    <script src="script.js"></script>

</body>
</html>

