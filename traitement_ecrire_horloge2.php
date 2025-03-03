<!DOCTYPE html>
<html>
<head>
    <title>Ecrire le lieu</title>
    <meta http-equiv="Content-Type" content="text/HTML; charset=utf-8" />
</head>
<body>
    <?php
    // Définition du nom du fichier JSON contenant les données des utilisateurs et des localisations
    $file = "users_modif.json";

    // Récupération du lieu choisi par l'utilisateur via la méthode GET
    $lieu = $_GET["lieu"];

    // Récupération de l'identifiant de l'utilisateur via la méthode GET
    $id = $_GET["user"];

    // Vérification de l'existence du fichier JSON
    if (is_file($file)) {
        // Lecture du contenu du fichier JSON et conversion en objet PHP
        $data = json_decode(file_get_contents($file));

        // Mise à jour du lieu de l'utilisateur dans l'objet PHP
        $data->personnes->$id->lieu = $_GET["lieu"];

        // Mise à jour de la date de mise à jour dans l'objet PHP
        $data->personnes->$id->date = (new DateTime())->format("Y-m-d H:i:s");

        // Enregistrement des modifications dans le fichier JSON
        file_put_contents($file, json_encode($data));

        // Récupération du lieu pour l'affichage
        $l = $data->personnes->$id->lieu;

        // Affichage d'un message indiquant le nouveau lieu de l'utilisateur
        echo "<p>" . $data->personnes->$id->prenom . " " . $data->personnes->$id->nom . " est " . $data->localisations->$l->text . ".</p>";
    }
    ?>
</body>
</html>