<?php ?>
<html>
    <div class="log-content">
        <!-- Tab links -->
        <div class="tab">
            <button class="tablinks active" onclick="openCity(event, 'connect')">Connection</button>
            <button class="tablinks" onclick="openCity(event, 'signin')">S'inscrire</button>
        </div>
        <!-- Tab content -->
        <div id="connect" class="tabcontent log" style="display:block;">
            <?php include 'conect.php' ?>
        </div>
        <div id="signin" class="tabcontent log">
            <?php include 'inscription.php' ?>
        </div>
        <!-- Remind Passowrd -->
        <div class="tab-footer">
            <div id="formFooter">
                <?php if(empty($_SESSION['pseudo'])) {?><a class="log-link" href="#">Mot de passe oublié [à coder]</a> <?php } ?>
            </div>
        </div>
    </div>
</html>
<script>
    function openCity(evt, choiceUser) {
    // Declare all variables
    var i, tabcontent, tablinks;

    // Get all elements with class="tabcontent" and hide them
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }

    // Get all elements with class="tablinks" and remove the class "active"
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }

    // Show the current tab, and add an "active" class to the button that opened the tab
    document.getElementById(choiceUser).style.display = "block";
    evt.currentTarget.className += " active";
    }
</script>
<script>
    e=true;
    document.getElementById("eye").style.color="red";
    function view(){
        if(e){
            document.getElementById("mdp").setAttribute("type", "text");
            document.getElementById("eye").style.color="green";
            e=false;
        }
        else{
            document.getElementById("mdp").setAttribute("type", "password");
            document.getElementById("eye").style.color="red";
            e=true;
        }
    }
</script>