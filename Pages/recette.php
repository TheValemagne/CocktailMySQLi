<?php
$recette = []; // liste des valeurs d'une recette
$recette_valide = true; // la recette existe bien
$liste_ingredients = []; // les ingrédients contenus dans la recette

if(isset($_GET['recette'])){
  // Connexion au serveur MySQL
  $mysqli= new mysql($host, $user, $pass, $base) or die("Problème de connection avec la base :".mysqli_error());

  $query = "SELECT *
            FROM `recettes`
            WHERE id_recette =".$mysqli->real_escape_string($_GET["recette"]);
            // récupère les informations de la recette
  $res_recette = $mysqli->query($query);
  $recette = $res_recette->fetch_assoc(); // [id_recette, titre, ingredients, preparation]

  if(sizeof($recette) > 0){ // la recette a été trouvée
    $query = "SELECT id_ingredient, nom
              FROM `index` NATURAL JOIN `ingredients`
              WHERE id_recette =".$mysqli->real_escape_string($_GET["recette"]);
              // recherche les ingrédients de la recette

    $res_ingredients = $mysqli->query($query);

    while($ingredient = $res_ingredients->fetch_assoc()){ // stocker les ingrédeints dans un tableau
      $liste_ingredients[] = $ingredient; // [id_ingredient, nom]
    }

  } else { // erreur lors de la recherche
    $recette_valide = false;
  }

  $mysqli->close();

} else { // erreur dans l'url
  $recette_valide = false;
}
?>
    <main>
<?php if(!$recette_valide) { // erreur la recette existe pas ?><p>
      Recette inexistante!
    </p>
<?php } else { // la recette existante dans la base de donnée ?>
      <h1><?php echo $recette['titre'] ?></h1>

      <img alt="<?php echo 'image recette n°'.$_GET["recette"] ?>" src="<?php echo getImageSrc(trim($recette['titre'])) ?>">

      <h2>préparation :</h2>

      <p><?php echo $recette['preparation'] ?></p>

      <h2>Listes des ingrédients :</h2>

      <ul>
        <?php // liens vers les ingredients de la recette
          $index = 0;
          $tri = (isset($_GET['tri']) && $authentifie && in_array($_GET['tri'], array('nom', 'nombre_recettes'))) ? "&tri=".$_GET['tri'] : "";

          foreach (preg_split('#\|#', $recette['ingredients']) as $ingredient) {
            if($index > 0){ // indentation du code
              echo "\t\t";
            }

            echo "<li>".$ingredient." (".getLienIngredient($liste_ingredients[$index]['id_ingredient'], $liste_ingredients[$index]['nom'], $tri).")</li>\n";
            $index++;
          }
        ?>
      </ul>

<?php } ?>
    </main>
