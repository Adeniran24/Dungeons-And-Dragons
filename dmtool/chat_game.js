document.addEventListener("DOMContentLoaded", function () {
    const chatBox = document.getElementById("chat-box");
    const messageInput = document.getElementById("message-input");
    const sendBtn = document.getElementById("send-btn");

    function fetchMessages() {
        fetch("get_messages.php")
            .then(response => response.json())
            .then(messages => {
                chatBox.innerHTML = "";
                messages.forEach(msg => {
                    const p = document.createElement("p");
                    p.textContent = `${msg.username}: ${msg.message}`;
                    chatBox.appendChild(p);
                });
                chatBox.scrollTop = chatBox.scrollHeight;
            });
    }

    sendBtn.addEventListener("click", function () {
        const message = messageInput.value.trim();
        if (message === "") return;

        fetch("send_message.php", {
            method: "POST",
            body: new URLSearchParams({ message }),
            headers: { "Content-Type": "application/x-www-form-urlencoded" }
        }).then(() => {
            messageInput.value = "";
            fetchMessages();
        });
    });

    setInterval(fetchMessages, 2000); // Refresh messages every 2 seconds
    fetchMessages(); // Load messages initially
});
