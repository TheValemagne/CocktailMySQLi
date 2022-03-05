    <main>
      <h1>Formulaire d'inscription</h1>

<?php if(sizeof($erreurs_inscription) > 0 || !isset($_POST["inscription"])) {
    include("Pages/formulaire.inc.php");

} else { // inscription réussie ?>
      <p>
        Félicitation, vous vous êtes bien inscrit!
      </p>

      <?php
      // Connexion au serveur MySQL
      $mysqli= new mysql($host, $user, $pass, $base) or die("Problème de connection avec la base :".mysqli_error());
      $mysqli->creerUtilisateur(trim($_POST['login']), trim($_POST['mot_de_passe']), trim($_POST['nom']), trim($_POST['prenom'])); // créer le compte
      $mysqli->close();
      ?>
    <?php } ?>

      <?php if(sizeof($erreurs_inscription) > 0) { // erreurs lors de l'inscription ?><p>
        Veuillez compléter correctement les champs suivants :
      </p>

      <ul>
        <?php
          $index = 0;

          foreach ($erreurs_messages as $champ) { // affichage des messages d'erreurs à l'utilisateur
            if($index > 0){ // indentation du code
              echo "\t\t";
            }

            echo "<li>$champ</li>\n";
            $index++;
          }
        ?>
      </ul><?php } ?>

    </main>
