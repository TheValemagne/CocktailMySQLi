<?php // fonctions pour le site web

/**
 * Transforme une chaine de carractères en paramètre d'url valide. Remplace les espages en _.
 *
 * @param string input, le nom à transformer
 * @return string paramètre d'url valide
 */
function strToUrl(string $input): string
{
  return str_replace(" ", "_", $input);
}

/**
 * Créer une ancre pour un ingrédient.
 *
 * @param int id_ingredient, l'identifiant unique de l'ingrédient
 * @param string nom_ingredient, le nom de l'ingrédient
 * @param string tri, l'option de tri si définie sur la page
 * @return string ancre envoyant à la page correspondant à l'ingrédient
 */
function getLienIngredient(int $id_ingredient, string $nom_ingredient, string $tri = ""): string
{
  return '<a href="index.php?page=ingredient&ingredient='.$id_ingredient.$tri.'">'.$nom_ingredient.'</a>';
}

/**
 * Créer une ancre pour une recette.
 *
 * @param int id_recette, l'identifiant unique de la recette
 * @param string titre_recette, le titre de la recette
 * @param string tri, l'option de tri si définie sur la page
 * @return string ancre envoyant à la page correspondant à l'ingrédient
 */
function getLienRecette(int $id_recette, string $titre_recette, string $tri = ""): string
{
  return '<a href="index.php?page=recette&recette='.$id_recette.$tri.'">'.$titre_recette.'</a>';
}

/**
 * Retourne le nom de l'image correspondant au titre du cocktail ou l'image par défaut si aucune image est assossiée au cocktail
 *
 * @param string titre_recette, le titre de la recette
 * @return string l'adresse de l'image de la recette donnée en entré
 */
function getImageSrc(string $titre_recette): string
{
  $search =  array('é', 'è', 'ä', 'â', "ç", "ï", "î", "û", "ü", "ñ", "-", "'");
  $replace = array('e', 'e', 'a', 'a', "c", "i", "i", "u", "u",  "n", "",  "");

  $nom_recette = str_replace($search, $replace, strtolower($titre_recette)); // enlève les accents et espace du nom
  $image = "./Photos/".ucwords(strToUrl($nom_recette)).".jpg";

  return file_exists($image) ? $image : "./Photos/cocktail.png"; // l'image existe pour la recette sinon image par défaut
}

/**
 * Retrouve tous les paramètres de l'url sauf le paramètre tri s'il existe
 *
 * @return array tableau avec les attibuts/valeurs de $_GET sans tri
 */
function getParametreUrl(): array
{
  $queries = [];

  foreach ($_GET as $nom => $valeur) {
    if($nom != 'tri'){
      $queries[] = "$nom=$valeur";
    }
  }

  return $queries;
}

?>
