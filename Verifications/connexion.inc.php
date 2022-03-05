<?php
if(isset($_POST["connexion"])){ // demande de connexion à un compte client
  if(isset($_POST["login"]) && isset($_POST["mot_de_passe"])){
    $login = trim($_POST["login"]);
    $mot_de_passe = trim($_POST["mot_de_passe"]);

    // Connexion au serveur MySQL
    $mysqli= new mysql($host,$user,$pass, $base) or die("Problème de connection avec la base :".mysqli_error());
    $utilisateur = $mysqli->obtenirUtilisateur($login); // récupère l'utilisateur avec le login unique -> [ login, mot_de_passe, nom, prenom ]
    $mysqli->close();

    if(sizeof($utilisateur) > 0 && $utilisateur['login'] === $login && $utilisateur["mot_de_passe"] === $mot_de_passe){ // login et mot de passe coincides

      foreach ($utilisateur as $donnee_utilisateur => $contenue_donnee) { // recupère et charge les données du client
        $_SESSION[$donnee_utilisateur] = $contenue_donnee;
      }

      if(isset($_GET["page"]) && $_GET["page"] == "inscription"){ // page d'inscription est interdite pour un client authentifié
        header("Location: ./index.php"); // redirection vers la page d'accueil
        exit;
      }
    }
  }
}

if(isset($_SESSION["login"]) && isset($_SESSION["mot_de_passe"])){ // authentification reussie
  $authentifie = true;
} else { // utilisateur non connecte ou erreur lors de la connection
  $authentifie = false;
}
?>
