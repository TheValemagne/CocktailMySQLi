<?php
if(isset($_POST["deconnexion"])){ // demande de deconnexion du compte actuel
  $_SESSION = array(); // vide le tableau de session
  session_destroy(); // arrête la session en cours

  if(isset($_GET["page"]) && $_GET['page'] == "monProfil"){ // page interdite pour un utilisateur non connecté
    header("Location: ./index.php"); // redirection vers la page d'accueil
  } else {
    header("Location: ".$_SERVER['REQUEST_URI']); // recharge la page actuelle
  }
  exit;
}
?>
