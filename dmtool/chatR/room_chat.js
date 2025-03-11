document.addEventListener("DOMContentLoaded", function () {
    const chatBox = document.getElementById("chat-box");
    const messageInput = document.getElementById("message-input");
    const sendBtn = document.getElementById("send-btn");
    const roomId = document.getElementById("room_id").value;
    let lastTimestamp = 0;  // Track last message timestamp

    function fetchMessages() {
        fetch(`get_room_messages.php?room_id=${roomId}&last_timestamp=${lastTimestamp}`)
            .then(response => response.json())
            .then(messages => {
                if (messages.length > 0) {
                    messages.forEach(msg => {
                        const p = document.createElement("p");
                        p.textContent = `${msg.username}: ${msg.message}`;
                        chatBox.appendChild(p);
                    });
                    chatBox.scrollTop = chatBox.scrollHeight;
                    lastTimestamp = messages[messages.length - 1].timestamp;  // Update last timestamp
                }
                setTimeout(fetchMessages, 3000); // Long polling: Fetch again only after a response
            })
            .catch(() => setTimeout(fetchMessages, 5000)); // Retry with delay if an error occurs
    }

    sendBtn.addEventListener("click", function () {
        const message = messageInput.value.trim();
        if (message === "") return;

        fetch("send_room_message.php", {
            method: "POST",
            body: new URLSearchParams({ room_id: roomId, message }),
            headers: { "Content-Type": "application/x-www-form-urlencoded" }
        }).then(response => response.json())
        .then(data => {
            if (data.success) {
                const p = document.createElement("p");
                p.textContent = `${data.username}: ${data.message}`;
                chatBox.appendChild(p);
                chatBox.scrollTop = chatBox.scrollHeight;
                messageInput.value = "";
                lastTimestamp = data.timestamp;
            }
        });
    });

    fetchMessages();
});
