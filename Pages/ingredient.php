<?php
$ingredient_valide = true; // l'ingrédient est valide
$liste_recettes = []; // liste de recettes utilisant l'ingrédient

if(isset($_GET['ingredient'])){
  // Connexion au serveur MySQL
  $mysqli= new mysql($host, $user, $pass, $base) or die("Problème de connection avec la base :".mysqli_error());

  $query = "SELECT *
            FROM `ingredients`
            WHERE `id_ingredient` = '".$mysqli->real_escape_string($_GET["ingredient"])."'";
            // recherche le nom de l'ingrédient, $_GET['ingredient'] contient l'id.

  $res = $mysqli->query($query);
  $ingredient = $res->fetch_assoc();

  if(sizeof($ingredient) > 0){
    $query = "SELECT id_recette, titre
              FROM `recettes` NATURAL JOIN `index`
              WHERE `id_ingredient` = '".$mysqli->real_escape_string($_GET["ingredient"])."'
              ORDER BY `titre`";
              // recherche toutes les recettes utilisant l'ingrédient affiché, trié par titre de recettes

    $res = $mysqli->query($query);

    while($recette = $res->fetch_assoc()){
      $liste_recettes[] = $recette; // [ id_recette, titre ]
    }
  } else {
    $ingredient_valide = false;
  }

  $mysqli->close();

  if(sizeof($liste_recettes) === 0){ // erreur, chaque ingrédient est au moins utilisé une fois (initialisation avec le tableau Recettes)
    $ingredient_valide = false;
  }

} else { // erreur dans l'url
  $ingredient_valide = false;
}
?>

<main>
<?php if(!$ingredient_valide) { // erreur l'ingredient n'existe pas ?><p>
  Ingrédient inexistant!
</p>
<?php } else { // l'ingrédient existe dans la base de donnée ?>
      <h1><?php echo $ingredient['nom'] ?></h1>

      <p>
        Liste de recettes contenant l'ingrédient :
      </p>

      <ul>
        <?php // affichage des liens de recettes utilisant l'ingrédient actuel
        $index = 0;
        $tri = (isset($_GET['tri']) && $authentifie && in_array($_GET['tri'], array('nom', 'nombre_recettes'))) ? "&tri=".$_GET['tri'] : "";

        foreach ($liste_recettes as $recette) {
          if($index > 0){ // indentation du code
            echo "\t\t";
          }

          echo '<li>'.getLienRecette($recette['id_recette'], $recette['titre'], $tri)."</li>\n";
          $index++;
        }
        ?>
      </ul>
<?php } ?>
    </main>
