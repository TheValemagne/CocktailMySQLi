<?php
if(sizeof($erreurs_inscription) == 0 && isset($_POST["modifier"]) && $authentifie) { // uniquement pour modifier un profil
  // Connexion au serveur MySQL
  $mysqli= new mysql($host,$user,$pass, $base) or die("Problème de connection avec la base :".mysqli_error());

  $res_affected_rows = $mysqli->modifierUtilisateur($_SESSION['login'], trim($_POST['mot_de_passe']), trim($_POST['nom']), trim($_POST['prenom']));
  $utilisateur = $mysqli->obtenirUtilisateur($_SESSION['login']); // données à jour
  $mysqli->close();

  if($res_affected_rows == 2){ // la mise à jour c'est bien passée
    foreach ($donnees_valides as $donnee) { // login et modifier ne sont pas pris en compte
        // Actualisation des données sauvegardées dans la session
        $_SESSION[$donnee] = trim($_POST[$donnee]);
    }

  } else {
    array_push($erreurs_inscription, "Erreur de la base de données");
  }
}
?>
