document.addEventListener("DOMContentLoaded", function () {
    const chatBox = document.getElementById("chat-box");
    const messageInput = document.getElementById("message-input");
    const sendBtn = document.getElementById("send-btn");
    const friendId = document.getElementById("friend_id").value;

    function fetchMessages() {
        fetch(`get_private_messages.php?friend_id=${friendId}`)
            .then(response => response.json())
            .then(messages => {
                chatBox.innerHTML = "";
                messages.forEach(msg => {
                    const p = document.createElement("p");
                    p.textContent = `${msg.sender}: ${msg.message}`;
                    chatBox.appendChild(p);
                });
                chatBox.scrollTop = chatBox.scrollHeight;
            });
    }

    sendBtn.addEventListener("click", function () {
        const message = messageInput.value.trim();
        if (message === "") return;

        fetch("send_private_message.php", {
            method: "POST",
            body: new URLSearchParams({ friend_id: friendId, message }),
            headers: { "Content-Type": "application/x-www-form-urlencoded" }
        }).then(() => {
            messageInput.value = "";
            fetchMessages();
        });
    });

    setInterval(fetchMessages, 2000);
    fetchMessages();
});
