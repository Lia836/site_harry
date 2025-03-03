<!DOCTYPE html>
<html>
<head>
    <title>Ecrire horloge</title>
    <meta http-equiv="Content-Type" content="text/HTML; charset=utf-8" />
</head>
<body>
    <?php
    // Définition du nom du fichier JSON contenant les données des utilisateurs et des localisations
    $file = "users_modif.json";

    // Vérification de l'existence du fichier
    if (is_file($file)) {
        // Lecture du contenu du fichier JSON et conversion en objet PHP
        $data = json_decode(file_get_contents($file));

        // Extraction des données des personnes et des localisations
        $users = $data->personnes;
        $localisations = $data->localisations;
    ?>

    <p>Je suis bien arrivé dans ce lieu</p>

    <!-- Formulaire permettant à l'utilisateur de choisir son nom et un lieu -->
    <form method="get" action="traitement_ecrire_horloge.php">
        <!-- Sélection de l'utilisateur -->
        <label for="user">Je suis : </label>
        <select name="user" id="user">
            <option value="0" selected disabled>-- Qui êtes vous ? --</option>
            <?php
            // Parcours de la liste des utilisateurs pour créer les options du menu déroulant
            foreach ($users as $key => $user) {
                // Chaque option a comme valeur l'identifiant unique de l'utilisateur
                // et affiche le prénom et le nom de l'utilisateur
                echo '<option value="' . $key . '">' . $user->prenom . ' ' . $user->nom . '</option>';
            }
            ?>
        </select><br><br>

        <!-- Sélection du lieu -->
        <?php
        // Parcours de la liste des localisations pour créer les boutons radio
        foreach ($localisations as $key => $loc) {
            // Chaque bouton radio a comme valeur le nom de la localisation
            // et affiche le nom de la localisation
            echo '<label>' . $key . '<input type="radio" name="lieu" value="' . $key . '" /></label><br />';
        }
        ?>

        <!-- Bouton d'envoi du formulaire -->
        <input type="submit" value="envoyer mon lieu" />
    </form>

    <?php
    } else {
        // Affichage d'un message d'erreur si le fichier JSON n'existe pas
        echo "<p>Le fichier " . $file . " n'existe pas.</p>";
    }
    ?>
</body>
</html>
