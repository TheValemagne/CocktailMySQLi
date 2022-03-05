<?php
// gestion des erreurs
$erreurs_inscription =  array(); // récupère les champs non valides
$erreurs_messages = array(); // récupère les messages d'erreurs à afficher pour l'utilisateur
$donnees_valides = array(); // récupère les champs valides à enregistrer si aucune erreur trouvée
$search =  array('é', 'è', 'ä', 'â', "ç", "ï", "î", "û", "ü", "-", "'", " ");
$replace = array('e', 'e', 'a', 'a', "c", "i", "i", "u", "u",  "",  "",  "");

// champs obligatoires :
if(isset($_POST["inscription"]) ){ // vérification du formulaire d'inscription

  if(!isset($_POST["login"]) || empty(trim($_POST["login"])) || !ctype_alnum(trim($_POST['login'])) ){
    // lettres non accentuées, minuscules ou majuscule et/ou chiffres
    array_push($erreurs_inscription, "login");
    array_push($erreurs_messages, "Le login est incorrect.");
  }

  // Connexion au serveur MySQL
  $mysqli= new mysql($host,$user,$pass, $base) or die("Problème de connection avec la base :".mysqli_error());
  $utilisateur = $mysqli->obtenirUtilisateur(trim($_POST["login"])); // récupère l'utilisateur
  $mysqli->close();

  if(sizeof($utilisateur) > 0 && !in_array("login", $erreurs_inscription)) {
    // login existe déjà dans la base de donnée
    array_push($erreurs_inscription, "login");
    array_push($erreurs_messages, "Le login existe déjà.");
  }
}

if(isset($_POST["inscription"]) || isset($_POST["modifier"])) { // vérification du formulaire d'inscription et de modification de profil

  if(!isset($_POST["mot_de_passe"]) || empty(trim($_POST["mot_de_passe"])) ){
    array_push($erreurs_inscription, "mot_de_passe");
    array_push($erreurs_messages, "Le mot de passe est invalide.");
  } else {
    array_push($donnees_valides, "mot_de_passe");
  }

  if(isset($_POST["nom"]) && !empty(trim($_POST["nom"])) ) {
    $nom = str_replace($search, $replace, strtolower(trim($_POST['nom'])));

    if(strlen($nom) < 2 || !ctype_alpha($nom)){
      array_push($erreurs_inscription, "nom");
      array_push($erreurs_messages, "Le nom est incorrect.");
    } else {
      array_push($donnees_valides, "nom");
    }
  } else {
    array_push($erreurs_inscription, "nom");
    array_push($erreurs_messages, "Le nom est incorrect.");
  }

  if(isset($_POST["prenom"]) && !empty(trim($_POST["prenom"])) ) {
    $prenom = str_replace($search, $replace, strtolower(trim($_POST['prenom'])));

    if(strlen($prenom) < 2 || !ctype_alpha($prenom) ){
      array_push($erreurs_inscription, "prenom");
      array_push($erreurs_messages, "Le prenom est incorrect.");
    } else {
      array_push($donnees_valides, "prenom");
    }
  } else {
    array_push($erreurs_inscription, "prenom");
    array_push($erreurs_messages, "Le prenom est incorrect.");
  }
}
?>
