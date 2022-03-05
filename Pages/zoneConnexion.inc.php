<?php if($authentifie) { // le client est connecté ?><ul>
            <li><?php
            if(isset($_SESSION["nom"]) && isset($_SESSION["prenom"]) ){ // client connecté avec nom et prenom connus (obligatoire)
              echo $_SESSION["nom"]." ".$_SESSION["prenom"];
            } ?></li>
            <li><a href="index.php?page=monProfil">Mon compte</a></li>
            <li>
              <form action="#" method="post" name="login">
                <input type="submit" name="deconnexion" value="Se déconnecter" />
              </form>
            </li>
          </ul>
<?php } else { // le client n'est pas connecté ou n'a pas pus se connecter ?><form action="#" method="post">
            Login <input type="text" name="login" value="<?php isset($_POST["login"]) ? $_POST['login'] : ""; ?>" />
            Mot de passe <input type="password" name="mot_de_passe" value="<?php isset($_POST["mot_de_passe"]) ? $_POST['mot_de_passe'] : ""; ?>" />
            <input type="submit" name="connexion" value="Se connecter" />
            <a href="index.php?page=inscription">s'inscrire</a>
          </form>

          <div><?php echo (isset($_POST['connexion']) && !$authentifie) ? "Login ou mot de passe invalide" : ""; ?></div>
<?php } ?>
