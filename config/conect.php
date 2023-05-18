<?php 
    session_start();
    include 'config/sqlusers.php';
    if(isset($_POST['Connect'])){
        $pseudo = htmlspecialchars($_POST['pseudo']);
        $mdp = sha1($_POST['mdp']);
        if(!empty($_POST['pseudo']) AND !empty($_POST['mdp'])){
            $verifpseudo = $bdd->prepare("SELECT * FROM users_log WHERE pseudo = ?");
            $verifpseudo-> execute(array($pseudo));
            if ($verifpseudo->rowCount()>0){
                $verifuser = $bdd->prepare("SELECT * FROM users_log WHERE pseudo = ? AND mdp= ?");
                $verifuser-> execute(array($pseudo, $mdp));
                if ($verifuser->rowCount() > 0) {
                    $_SESSION['pseudo'] = $verifuser->fetch()['pseudo'];
                    $success = "Bon retour parmis nous ".$_SESSION['pseudo']." !<br><br> Votre T'chat ce chargera dans quelque secondes ";
                    echo '<script>setTimeout(function() {window.location.reload();}, 5000);</script>';
                }
                else{
                    $erreur = "Mot de passe incorrect";
                }
            }
            else{
                $erreur = "Ce pseudo n'existe pas";
            }
        }
    }
    if(!empty($_SESSION['pseudo'])){
        if(!empty($success)) { ?><div class="success"><?= $success ?></div><?php };
    } 
    else{
        if(!empty($erreur)) { ?><div class="warn"><?= $erreur ?></div> <?php } ?>
        <form method="POST" action="">
            <div class="log-content-input">
                <input type="text" class="log-input" name="pseudo" required placeholder="Pseudo" autocomplete="off" />
            </div>
            <div class="log-content-input">
                <input type="password" id="mdp" class="log-input" name="mdp" required placeholder="Mot de passe" autocomplete="off" />
                <span id="eye" onClick="view()"><i class="fas fa-eye-slash"></i></span>
            </div>
            <input type="submit" class="log-button" name="Connect">
        </form>
    <?php } ?>