<?php // changement de mise en forme en fonction de la page actuelle
if(isset($_GET['page']) && $_GET['page'] == "inscription"){ // inscription, le login doit être saisie
  $option = 'required="required"';
  $nom_submit = "inscription";
  $valeur_submit = "S'inscrire";
} else if (isset($_GET['page']) && $_GET['page'] == "monProfil"){ // modification compte, le login ne peut pas être changé
  $option = 'disabled="disabled"';
  $nom_submit = "modifier";
  $valeur_submit = "modifier";
}
?>
      <form action="#" method="post">

        <fieldset>
          <legend>Connexion</legend>
          <label for="login">Login :</label>
            <input type="text" <?php if(in_array("login", $erreurs_inscription)) { echo 'class="error"'; } ?> name="login" id="login" value="<?php if(isset($_POST["login"])) {echo $_POST["login"]; }; ?>" <?php echo $option; ?> />
          <br />
          <label for="mot_de_passe">Mot de passe :</label>
            <input type="password" <?php if(in_array("mot_de_passe",$erreurs_inscription)) { echo 'class="error"'; } ?> name="mot_de_passe" id="mot_de_passe" value="<?php if(isset($_POST["mot_de_passe"])) { echo $_POST["mot_de_passe"]; }; ?>" required="required" />
          <br />
        </fieldset>

        <fieldset>
          <legend>Informations personnelles</legend>

          <label for="nom">Nom : </label>
            <input type="text" <?php if(in_array("nom", $erreurs_inscription)) { echo 'class="error"'; } ?> name="nom" id="nom" value="<?php if(isset($_POST["nom"])) { echo $_POST["nom"]; }; ?>" required="required" />
          <br />
          <label for="prenom">Prénom : </label>
            <input type="text" <?php if(in_array("prenom", $erreurs_inscription)) { echo 'class="error"'; } ?> name="prenom" id="prenom" value="<?php if(isset($_POST["prenom"])) { echo $_POST["prenom"]; }; ?>" required="required" />
        </fieldset>
        <br />

        <input type="submit" name="<?php echo $nom_submit ?>" value="<?php echo $valeur_submit ?>" />

      </form>

      <p>
        Tous les champs sont obligatoires.
      </p>
