<?php
include("Donnees.inc.php");

// Connexion au serveur MySQL
$mysqli= new mysql($host, $user, $pass) or die("Problème de création de la base :".mysqli_error());

$mysqli->creationBase($base);
$mysqli->initialisation($Recettes);

$mysqli->close();
?>

		<main>
			<h1>Initialisation réussie</h1>

			<p>
				La base de données a bien été initialisée. Vous pouvez commencer à naviguer! <a href="index.php" >La page d'accueil</a>
			</p>
		</main>
