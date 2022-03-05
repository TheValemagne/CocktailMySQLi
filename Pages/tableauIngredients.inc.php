<aside>
  <?php // uniquement pour un utilisateur authentifié
  $tri = ( isset($_GET['tri']) && in_array($_GET['tri'], array('nom', 'nombre_recettes')) ) ? $_GET['tri'] : 'nom';

  $mysqli= new mysql($host, $user, $pass, $base) or die("Problème de connection avec la base :".mysqli_error());
  $tableau_ingredients = $mysqli->obtenirTableau($tri);
  $mysqli->close();

  $queries = getParametreUrl(); // variables GET sans l'attribut 'tri' s'il existe -> par défaut, liste triée par aliments
  $queries_nombre_recettes = array_merge($queries, array("tri=nombre_recettes")); // variables GET pour le tri par nombre de recette du tableau
  ?>

  <table>
    <thead>
      <tr>
        <th>
          <a href="index.php?<?php echo implode("&", $queries_nombre_recettes) ?>"
            <?php echo (isset($_GET['tri']) && $_GET['tri'] =='nombre_recettes') ? 'class="tri_actif triangle-desc"' : "" ?> >Nombre de recettes</a>
        </th>
        <th>
          <a href="index.php<?php echo (sizeof($queries) > 0) ? '?'.implode("&", $queries) : ''; ?>"
            <?php echo (!isset($_GET['tri']) || (isset($_GET['tri']) && $tri == 'nom') ) ? 'class="tri_actif triangle-asc"' : "" ?> >Aliment</a>
        </th>
      </tr>
    </thead>
    
    <tbody>
      <?php foreach ($tableau_ingredients as $ingredient) { ?><tr>
        <td><?php echo $ingredient['nombre_recettes'] ?></td>
        <td>
          <?php
          if(isset($_GET['tri'])){ // le paramètre de tri est déclaré dans l'url
            echo getLienIngredient($ingredient['id_ingredient'], $ingredient['nom'], "&tri=".$tri);
          } else { // pas de paramètre de tri dans l'url
            echo getLienIngredient($ingredient['id_ingredient'], $ingredient['nom']);
          }
          ?>

        </td>
      </tr>
      <?php } ?>

    </tbody>
  </table>

</aside>
