<?php

class MySql extends mysqli {

  /**
   * Création de l'objet MsSql qui étand mysqli de PHP.
   *
   * @param string host, l'adresse du site
   * @param string user, l'utilisateur
   * @param string pass, le mot de passe
   * @param string base, le nom de la base de donnéees à créer, optionel
   */
  public function __construct($host, $user, $pass, $base = null){
    if($base === null){
      parent::__construct($host,$user, $pass);
    } else {
      parent::__construct($host,$user, $pass, $base);
    }
  }

  /**
   * Excécution d'une requette avec gestion d'erreur
   *
   * @param string query, la requette SQL à excécuter
   * @return mysqli_result|bool
   */
  public function query($query) {
    $res = parent::query($query) or die("$query : ".mysqli_error($this));
    return $res;
  }

  /**
   * Création des tables de la base de données : Recettes, Ingrédients, Index et Utilisateurs
   *
   * @param string base, le nom de la base de donnéees à créer
   * @return void
   */
  public function creationBase($base){
    // Suppression / Création / Sélection de la base de données : $base
    $this->query('DROP DATABASE IF EXISTS '.$base); // Suppression de la base de données existante
  	$this->query('CREATE DATABASE '.$base); // création d'une base de données vide

    // Sélection de la base vide
    $this->select_db($base) or die("Impossible de sélectionner la base : $base");

    // table contenant les recettes de cocktails
    $query = "CREATE TABLE `recettes` (
      `id_recette` SMALLINT(6) NOT NULL ,
      `titre` VARCHAR(100) NOT NULL,
      `ingredients` VARCHAR(200) NOT NULL,
      `preparation` VARCHAR(800) NOT NULL,
      PRIMARY KEY (`id_recette`)
    )";

  	$this->query($query);

    // table contenant les ingrédients des différents cocktails
    $query = "CREATE TABLE `ingredients` (
      `id_ingredient` SMALLINT(6) NOT NULL ,
      `nom` VARCHAR(60) NOT NULL,
      PRIMARY KEY (`id_ingredient`),
      UNIQUE (`nom`)
    )";

  	$this->query($query);

    // table associant à chaque recette ses ingrédients
    $query = "CREATE TABLE `index` (
      `id_recette` SMALLINT(6) NOT NULL ,
      `id_ingredient` SMALLINT(6) NOT NULL,
      PRIMARY KEY (`id_recette`, `id_ingredient`),
      FOREIGN KEY (`id_recette`) REFERENCES `recettes`(`id_recette`) ON DELETE CASCADE,
      FOREIGN KEY (`id_ingredient`) REFERENCES `ingredients`(`id_ingredient`) ON DELETE CASCADE
    )";

  	$this->query($query);

    // table pour les comptes utilisateurs
    $query = "CREATE TABLE `utilisateurs` (
      `login` VARCHAR(20) NOT NULL,
      `mot_de_passe` VARCHAR(20) NOT NULL,
      `nom` VARCHAR(40) NOT NULL,
      `prenom` VARCHAR(40) NOT NULL,
      PRIMARY KEY (`login`)
    )";

  	$this->query($query);
  }

  /**
   * initialisation de la base de données avec un tableau de recettes
   *
   * @param array recettes, le tableau contenant les recettes avec un titre, des ingrédients et une préparation
   * @return void
   */
  public function initialisation($recettes){
    $id_ingredient = 1; // identifiant unique pour les ingrédients
    $liste_id_ingredients = []; // association de l'id à l'ingrédient, nécessaire pour la table 'index'
    $liste_ingredients = []; // liste formatée de chaque ingrédient pour une requette SQL insert
    $liste_recettes = []; // liste formatée de chaque recette pour une requette SQL insert
    $liste_index = []; // liste formaté de chaque index pour une requette SQL insert

    foreach ($recettes as $index => $recette) {
      $id_recette = $index + 1; // id_recette > 0, l'identifiant unique de la recette
      $titre = $this->real_escape_string($recette['titre']); // le titre de la recette actuelle
      $ingredients_details = $this->real_escape_string($recette['ingredients']); // liste détaillée des ingrédients
      $preparation = $this->real_escape_string($recette['preparation']); // préparation de la recette

      // recette -> (id_recette, titre, ingredients, preparation)
      $liste_recettes[] = '('.$id_recette.', "'.$titre.'", "'.$ingredients_details.'", "'.$preparation.'")';

      foreach ($recette['index'] as $ingredient) { // parcours des ingrédients de la recette
        if(!isset($liste_id_ingredients[$ingredient])){ // l'ingrédient est "inconnu" dans la liste, attribution d'un identifiant
          $liste_id_ingredients[$ingredient] = $id_ingredient++;

          // ingredient -> (id_ingredient, nom)
          $liste_ingredients[] = '("'.$liste_id_ingredients[$ingredient].'", "'.$this->real_escape_string($ingredient).'")';
        }

        // index -> (id_recette, id_ingredient) : association recette/ingrédient
        $liste_index[] = '('.$id_recette .', "'.$liste_id_ingredients[$ingredient].'")';
      }
    }

    // Insertion de toutes les valeurs collectées
    $query = "INSERT INTO `recettes` VALUES ".implode(", ", $liste_recettes).";";
  	$this->query($query); // insertion de toutes les recettes dans la table

    $query = "INSERT INTO `ingredients` VALUES ".implode(", ", $liste_ingredients).";";
    $this->query($query); // insertion de tous les ingrédients dans la table

    $query = "INSERT INTO `index` VALUES ".implode(", ", $liste_index).";";
    $this->query($query); // insertion de toutes les associations recette/ingrédient dans la table
  }

  /**
   * Création et enregistrement d'un compte utilisateur
   *
   * @param string login, doit être unique, lettres non accentuées, minuscules ou majuscule et/ou chiffres
   * @param string mot_de_passe, le mot de passe
   * @param string nom, le nom du client, contient pas de chiffres ou carratères spétiaux
   * @param string prenom, le prénom du client, contient pas de chiffres ou carratères spétiaux
   * @return int nombre de lignes affectées
   */
  public function creerUtilisateur($login, $mot_de_passe, $nom, $prenom){
    $query = "INSERT INTO `utilisateurs` VALUES(?, ?, ?, ?)"; // requette d'ajout d'utilisateur
    $stmt = $this->prepare($query);
    $stmt->bind_param('ssss', $login, $mot_de_passe, $nom, $prenom);
    $stmt->execute();

    return $this->affected_rows;
  }

  /**
   * Modification d'un compte utilisateur existant
   *
   * @param string login, doit exister
   * @param string mot_de_passe, le mot de passe
   * @param string nom, le nom du client, contient pas de chiffres ou carratères spétiaux
   * @param string prenom, le prénom du client, contient pas de chiffres ou carratères spétiaux
   * @return int nombre de lignes affectées
   */
  public function modifierUtilisateur($login, $mot_de_passe, $nom, $prenom){
    $query = "REPLACE INTO `utilisateurs` VALUES(?, ?, ?, ?)"; // requette de mise à jour d'utilisateur
    $stmt = $this->prepare($query);
    $stmt->bind_param('ssss', $login, $mot_de_passe, $nom, $prenom);
    $stmt->execute();

    return $this->affected_rows;
  }

  /**
   * Récupération du compte utilisateur.
   *
   * @param string login, doit exister
   * @return array|null|false -> [login, mot_de_passe, nom, prenom]
   */
  public function obtenirUtilisateur(string $login){
    $query = "SELECT * FROM `utilisateurs` WHERE login = '".$this->real_escape_string($login)."'"; // information du client, s'il existe
    $res = $this->query($query);

    return $res->fetch_assoc();
  }

  /**
   * Récupération d'un tableau avec le nom de recettes pour chaque ingrédient avec la possibilité d'indiquer un tri.
   *
   * @param string tri du resultat
   * @return array|null|false -> [id_ingredient, nom, nombre_recette] pour chaque ingredient
   */
  public function obtenirTableau(string $tri){
    $tableau_ingredients = [];
    $ordre = ($tri === "nombre_recettes") ? "DESC" : "ASC"; // ordre de tri du résultat

    $query = "SELECT id_ingredient, nom, COUNT(id_recette) AS nombre_recettes
              FROM `index` NATURAL JOIN `ingredients`
              GROUP BY nom
              ORDER BY ".$tri." ".$ordre;

    $res = $this->query($query);

    while($ingredient = $res->fetch_assoc()){
      $tableau_ingredients[] = $ingredient; // stockage des informations collectés
    }

    return $tableau_ingredients;
  }
}
?>
