<?php
    include 'config/sqltchat.php';
    if (isset($_POST['SendMessage'])) {
        if (!empty($_POST['message']) && !empty($_SESSION['pseudo'])) {
            $pseudo = htmlspecialchars($_SESSION['pseudo']);
            $message = nl2br(htmlspecialchars($_POST['message']));
            if ($_SESSION['last_message'] == $message) {
                $erreur = "[ALERT-SPAM] - Vous avez déjà envoyé ce message.";
            } else {
                $insertTchat = $bdd->prepare('INSERT INTO messages(pseudo, message) VALUES(?, ?)');
                $insertTchat->execute([$pseudo, $message]);
                $_SESSION['last_message'] = $message;
                header("Location: index.php?page=contact");
                exit;
            }
        } else {
            $erreur = "Votre message est vide";
        }
    }
?>
<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="utf-8">
        <title>T'Chat Box</title>
        <link rel="stylesheet" type="text/css" href="config/tchat.css">
        <link rel="stylesheet" type="text/css" href="config/log.css">
        <link rel="stylesheet" type="text/css" href="config/globalcolor.css">
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>
        <script src="https://kit.fontawesome.com/cf3e147144.js" crossorigin="anonymous"></script>
    </head>
    <body>
        <div class="containt-global containt-global-contact">
            <div class="chat-box">
                <div class="chat-header">
                    <h2>T'chat Box</h2>
                    <?php if(!empty($_SESSION['pseudo'])){?>
                    <div class="dropdown">
                        <button class="config-btn dropbtn"><i class="fas fa-palette"></i></button>
                        <div class="dropdown-content">
                            <a href="#">Changer l'arriere plant</a>
                            <a href="#">Personalisation des couleurs</a>
                            <a href="#">Changer mon avatar</a>
                            <a href="config/disconect.php">Deconnection</a>
                        </div>
                    </div><?php }?>
                </div>
                <?php if(!empty($_SESSION['pseudo'])){?>
                    <div class="chat-container">
                        <div class="chat-body" id="chatBody">
                            <div class="load-more-messages">
                                <a class="load-more-btn" onclick="loadMoreMessages()">Voir les anciens messages</a><hr>
                            </div>
                            <div id="messages"></div>
                        </div>
                        <div id="scrollToBottomButton" class="scroll-to-bottom-button">
                            <i class="fas fa-chevron-down"></i>
                        </div>
                    </div>
                    <?php if(!empty($erreur)){?><div class="warn"><?= $erreur ?></div><?php } ?>
                    <div class="chat-footer">
                        <form method="POST" action=""> 
                            <input type="text" name="message" placeholder="Entrer votre message ici...">
                            <button class="send-btn" type="submit" name="SendMessage"><i class="fas fa-paper-plane"></i></button>
                        </form>
                    </div>
                <?php } else { ?>
                    <div class="chat-body chat-logs">
                        <?php include 'config/log.php' ?>
                    </div>
                    <div class="chat-footer">
                        <form method="POST" action="">
                            <input type="text" placeholder="Entrez votre message ici..." disabled>
                            <button class="send-btn" type="submit" disabled><i class="fas fa-paper-plane"></i></button>
                        </form>
                    </div>
                <?php } ?>
            </div> 
        </div>
    </body>
</html>
<script>
    var messages = [];
    var displayedMessages = [];
    var limit = 30;
    var currentIndex = 0;
    var previousScrollHeight = 0;
    $(document).ready(function() {
        loadMessages();
        $('#chatBody').scroll(function() {
            var chatBody = $(this);
            var scrollToBottomButton = $('#scrollToBottomButton');
            if (chatBody.scrollTop() < chatBody[0].scrollHeight - chatBody.outerHeight() - 50) {
                scrollToBottomButton.show();
            } else {
                scrollToBottomButton.hide();
            }
        });
        $('#scrollToBottomButton').click(function() {
            scrollChatToBottom();
            $(this).hide();
        });
    });
    function loadMessages() {
      $.ajax({
        url: 'config/loadMessages.php',
        type: 'GET',
        success: function (response) {
          messages = JSON.parse(response);
          displayedMessages = messages.slice(0, limit);
          currentIndex = limit;
          displayMessages(displayedMessages);
          scrollChatToBottom();
        },
      });
    }
    function loadMoreMessages() {
        var chatBody = $('#messages').parent();
        previousScrollHeight = chatBody[0].scrollHeight - chatBody.scrollTop();
        var remainingMessages = messages.slice(currentIndex, currentIndex + limit);
        currentIndex += limit;
        if (remainingMessages.length === 0) {
            $('.load-more-messages').hide();
            return;
        }
        displayedMessages = displayedMessages.concat(remainingMessages);
        displayMessages(displayedMessages);
        chatBody.scrollTop(chatBody[0].scrollHeight - previousScrollHeight);
        checkScrollToBottomButton();
    }
    function displayMessages(messages) {
        var chatBody = $('#messages');
        chatBody.empty();
        messages.forEach(function (message) {
            var messageHTML = '';
            if (message.pseudo === '<?php echo $_SESSION['pseudo']; ?>') {
            messageHTML += '<div class="message sent">';
            } else {
            messageHTML += '<div class="message received">';
            }
            messageHTML += '<span class="chat-logo"><img src="./images/logo/messo.png"></span>';
            messageHTML +=
            '<p><span class="chat-name">' +
            message.pseudo +
            '</span> ~ <span class="time">' +
            message.time +
            '</span><br>';
            messageHTML += message.message + '</p>';
            messageHTML += '</div>';
            chatBody.prepend(messageHTML);
        });
        if (currentIndex > limit) {
            var chatBody = $('#messages').parent();
            chatBody.scrollTop(chatBody[0].scrollHeight - previousScrollHeight);
        } else {
            scrollChatToBottom();
        }
        checkScrollToBottomButton();
    }
    function scrollChatToBottom() {
      var chatBody = $('#messages').parent();
      chatBody.scrollTop(chatBody[0].scrollHeight);
      checkScrollToBottomButton();
    }
    function checkScrollToBottomButton() {
        var chatBody = $('#messages').parent();
        var scrollToBottomButton = $('#scrollToBottomButton');

        if (chatBody.scrollTop() + chatBody.height() < chatBody[0].scrollHeight) {
            scrollToBottomButton.show();
        } else {
            scrollToBottomButton.hide();
        }
    }
</script>