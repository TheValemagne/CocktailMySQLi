<?php // PHP 7.1.3
// inclusion des verifications
session_start();

//base de données
include("mysqli/Parametres.php"); // Parametres de configuration de la connexion
include("mysqli/mysql.class.php"); // gestion de la base de données avec mysqli

// verifications
include("Verifications/deconnexion.inc.php"); // gestion de la déconnexion d'un compte
include("Verifications/connexion.inc.php"); // gestion de la connexion à un compte existant
include("Verifications/formulaire.inc.php"); // vérification du formulaire d'inscription / modification compte
include("Verifications/validationProfil.php"); // met à jour la base de données lors d'un changement du profil

// fonctions
include("Fonctions/fonctions.inc.php"); // fichier de définition des fonctions

$pages_authentifiees = array("monProfil", "ingredient", "install", "recette", "recettes"); // les pages autorisées pour un client connecté
$pages_non_authentifiees = array("inscription", "ingredient", "install", "recette", "recettes"); // les pages autorisées pour un client non connecté
?>
<!DOCTYPE html>

<html lang ="fr">

  <head>
    <meta charset="utf-8" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" href="style.css" media="screen"/>
    <link rel="icon" type="image/png" href="Photos/cocktail.png" />
  	<title>Cocktails <?php echo isset($_GET["page"]) ? $_GET["page"] : "" ?></title>
  </head>

  <body>
  	<header>
      <ul>
        <li>
          <form action="index.php?page=install" method="post">
            <input type="submit" name="install" value="installer" />
          </form>
        </li>
        <li>
          <a href="index.php<?php echo (isset($_GET['tri']) && $authentifie) ? "?tri=".$_GET['tri'] : ""; ?>">Accueil</a>
        </li>
        <li>
          <?php include("Pages/zoneConnexion.inc.php"); ?>
        </li>
      </ul>
  	</header>

<?php if($authentifie && !(isset($_GET['page']) && $_GET['page'] == "monProfil")) { // pas de tableau pour la page de profil et utilisateur non connecté
    include("Pages/tableauIngredients.inc.php"); // tableau avec la liste de tous les ingrédients et le nombre de recettes les utilisant
} ?>

<?php
  if(isset($_GET["page"])) {
    if($authentifie && in_array($_GET["page"], $pages_authentifiees) ){ // utilisateur connecté
      include("Pages/".$_GET["page"].".php");
    } else if(!$authentifie && in_array($_GET["page"], $pages_non_authentifiees) ){ // utilisateur non connecté
      include("Pages/".$_GET["page"].".php");
    } else { // page inexistante ou interdite
      include("Pages/404.html");
    }
  } else { // page d'acceuil par défaut
    include("Pages/recettes.php");
  }
?>

  </body>
</html>
