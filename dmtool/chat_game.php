<?php
include '../session_token.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat/Game Session</title>
    <script src="chat_game.js" defer></script>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; }
        #chat-box { width: 80%; height: 300px; border: 1px solid #ccc; overflow-y: auto; margin: 20px auto; padding: 10px; }
        #message-input { width: 70%; padding: 10px; }
        #send-btn { padding: 10px; cursor: pointer; }
    </style>
</head>
<body>
    <h1>Welcome, <?php echo htmlspecialchars($username); ?>!</h1>
    <div id="chat-box"></div>
    <input type="text" id="message-input" placeholder="Type a message...">
    <button id="send-btn">Send</button>
</body>
</html>
