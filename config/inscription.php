<?php 
    session_start();
    include 'config/sqlusers.php';
    if(isset($_POST['Register'])){
        $pseudo = htmlspecialchars($_POST['pseudo']);
        $mdp = sha1($_POST['mdp']);
        $mdp2 = sha1($_POST['mdp2']);
        $mail = htmlspecialchars($_POST['mail']);
        $mail2 = htmlspecialchars($_POST['mail2']);
        $ip = $_SERVER['REMOTE_ADDR'];    
        if(!empty($_POST['pseudo']) AND !empty($_POST['mdp']) AND !empty($_POST['mdp2']) AND !empty($_POST['mail']) AND !empty($_POST['mail2'])){
            $pseudolenght = strlen($pseudo);
            if ($pseudolenght <= 255){
                if($mail == $mail2){
                    $reqmail = $bdd->prepare("SELECT * FROM users_log WHERE mail = ?");
                    $reqmail-> execute(array($mail));
                    $mailexist = $reqmail->rowCount();
                    if($mailexist == 0){
                        if($mdp == $mdp2){
                            $verifpseudo = $bdd->prepare("SELECT * FROM users_log WHERE pseudo = ?");
                            $verifpseudo-> execute(array($pseudo));
                            $pseudoexist = $verifpseudo->rowCount();
                            if($pseudoexist == 0){
                                $insertUser = $bdd->prepare('INSERT INTO users_log(pseudo, mdp, mail, ip)VALUES (?, ?, ?, ?) ');
                                $insertUser->execute(array($pseudo, $mdp, $mail, $ip));
                                $success = "Votre compte à bien été crée ! Bienvenue sur Messoland =) ".$_SESSION['pseudo']."<br><br> Votre T'chat ce chargera dans quelque secondes ";
                                echo '<script>setTimeout(function() {window.location.reload();}, 5000);</script>';
                            }
                            else{
                                $erreur = "Ce pseudo est déjà utilisé";
                            }
                        }
                        else{
                            $erreur = "Les mots de passes ne correspondent pas";
                        }
                    }
                    else{
                        $erreur = "Cette adresse mail est déjà utilisé";
                    }
                }
                else{
                    $erreur = "Les adresses mails ne correspondent pas";
                }
            }
            else{
                $erreur = "Votre pseudo est trop long";
            }
        }
    }
    if(!empty($_SESSION['pseudo'])){
        if(!empty($success)) { ?><div class="success"><?= $success ?></div><?php }
    }
    else{
        if(!empty($erreur)) { ?> <div class="warn"><?= $erreur ?></div> <?php } ?>
    <form action="/" method="post">
        <input type="text" class="log-input" name="pseudo" required placeholder="Pseudo" autocomplete="off" />
        <input type="email" class="log-input" name="mail" required placeholder="Adresse Email" autocomplete="off"/>
        <input type="email" class="log-input" name="mail2" required placeholder="Confirmation adresse email" autocomplete="off"/>
        <input type="mdp" class="log-input" name="mdp" required placeholder="Mot de passe" autocomplete="off"/>
        <input type="mdp" class="log-input" name="mdp2" required placeholder="Confirmation mot de passe" autocomplete="off"/>
        <button type="submit" class="log-button" name="Register">Valider</button>
     </form>
    <?php } ?>