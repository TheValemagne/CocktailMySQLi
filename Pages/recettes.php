    <main>

      <h1>Listes des recettes</h1>

      <ul>
        <?php
          $liste_ingredients = []; // liste des ingrédients pour chaque recette
          $tri = (isset($_GET['tri']) && $authentifie && in_array($_GET['tri'], array('nom', 'nombre_recettes'))) ? "&tri=".$_GET['tri'] : "";

          $mysqli= new mysql($host, $user, $pass, $base) or die("Problème de connection avec la base :".mysqli_error());

          $query = "SELECT id_recette, titre
                    FROM `recettes`
                    ORDER BY `titre`";
                    // récupère toutes les recettes, trié par ordre alphabétique des titres de recettes
          $res_recettes = $mysqli->query($query);

          $query = "SELECT *
                    FROM `index` NATURAL JOIN `ingredients`
                    ORDER BY `nom`";
                    // récupère toutes les associations recette/ingrédient, trié par ordre alphabétique des noms d'ingrédients
          $res_index = $mysqli->query($query);
          $mysqli->close();

          while($index = $res_index->fetch_assoc()){ // récupère les ingrédiants et les stockent dans un tableau pour chaque recette
            $liste_ingredients[$index['id_recette']][] = $index; // [ id_recette, id_ingredient, nom ]
          }

          while($recette = $res_recettes->fetch_assoc()){ // affichage de chaque recette de la base de données ?>

        <li>
          <h2><?php echo getLienRecette($recette['id_recette'], $recette['titre'], $tri)// lien vers la page dédiée ?></h2>
          <ul>
            <?php
              $index = 0;

              foreach ($liste_ingredients[$recette['id_recette']] as $ingredient) { // affichage des ingrédients de la recette actuelle (+ lien)
                if($index > 0){ // indentation du code
                  echo "\t\t\t";
                }

                echo "<li>".getLienIngredient($ingredient['id_ingredient'], $ingredient['nom'], $tri)."</li>\n";
                $index++;
              }
            ?>
          </ul>
        </li>

      <?php } ?></ul>

    </main>
