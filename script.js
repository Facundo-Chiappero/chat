function isLastMessageVisible() {
    const messagesContainer = document.getElementById('messages');
    const lastMessage = document.getElementsByClassName('chatEnd')[0];
    
    const lastMessageRect = lastMessage.getBoundingClientRect();
    const containerRect = messagesContainer.getBoundingClientRect();
    
    return lastMessageRect.bottom <= containerRect.bottom && lastMessageRect.top >= containerRect.top;
}

function handleScroll() {
    const btnArrowDown = document.getElementById('btnarrow-down');
    
    if (isLastMessageVisible()) {
        btnArrowDown.style.display = 'none';
    } else {
        btnArrowDown.style.display = 'block';
    }
}

function scrollToBottom() {
    const chatEnd = document.getElementsByClassName("chatEnd")[0];
    chatEnd.scrollIntoView({ behavior: "smooth" });
}

function loadMessages() {
    fetch('get_messages.php')
    .then(response => response.text())
    .then(data => {
        document.getElementById('messages').innerHTML = data;
        handleScroll();
        scrollToBottom();
    })
    .catch(error => console.error('Error loading messages:', error));
}

function deleteMsg(id) {
    const modal = document.getElementsByClassName("modal")[0];
    modal.style.display = "flex";

    const deleteButton = document.getElementById("deletebtn");

    const deleteHandler = () => {
        fetch('delete_message.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'id=' + id
        })
        .then(response => response.text())
        .then(data => {
            if (data.trim() === "Mensaje eliminado correctamente") {
                document.getElementById(id).remove();
            } else {
                alert("Error al eliminar el mensaje");
            }
            modal.style.display = "none";
        })
        .catch(error => {
            console.error('Error:', error);
            modal.style.display = "none";
        });
    deleteButton.removeEventListener("click", deleteHandler);
    };

    deleteButton.addEventListener("click", deleteHandler);

    const cancelButton = document.getElementById("cancelbtn");
    cancelButton.addEventListener("click", () => {
        modal.style.display = "none";
        deleteButton.removeEventListener("click", deleteHandler);

    });
}

document.getElementById('messages').addEventListener('scroll', handleScroll);

document.getElementById('chatForm').addEventListener('submit', (e) => {
    e.preventDefault();    
    
    var inputText = document.getElementById('input').value.trim();
    
    if(inputText.length < 1) return;
    
    fetch('insert.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: 'input=' + encodeURIComponent(inputText)
    })
    .then(() => {
        document.getElementById('input').value = "";
        loadMessages();
    })
    .catch(error => console.error('Error:', error));
});

function scrollBtn(){
    scrollToBottom();
    document.getElementById('btnarrow-down').style.display = 'none';
};

loadMessages();
