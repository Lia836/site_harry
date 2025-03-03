<!DOCTYPE html>
<html>
<head>
    <title>Lire le lieu</title>
    <meta http-equiv="Content-Type" content="text/HTML; charset=utf-8" />
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php
    // Définition du nom du fichier JSON contenant les données des utilisateurs et des localisations
    $file = "users_modif.json";

    // Vérification de l'existence du fichier JSON
    if (is_file($file)) {
        // Lecture du contenu du fichier JSON et conversion en objet PHP
        $data = json_decode(file_get_contents($file));

        // Extraction des données des personnes et des localisations
        $personnes = $data->personnes;
        $localisations = $data->localisations;
    ?>

    <div class="clock">
        <?php
        // Parcours de la liste des localisations
        foreach ($localisations as $nom => $coords):
        ?>
            <div class="location" id="<?php echo htmlspecialchars($nom) ?>">
                <img src="images/<?php echo htmlspecialchars($coords->img) ?>" alt="<?php echo htmlspecialchars($nom) ?>">
            </div>
        <?php
        endforeach;
        ?>

        <?php
        // Parcours de la liste des personnes
        foreach ($personnes as $key => $user):
        ?>
            <div class="aiguille" data-lieu="<?php echo htmlspecialchars($user->lieu) ?>">
                <img src="images/<?php echo htmlspecialchars($user->img) ?>" alt="<?php echo htmlspecialchars($user->prenom) ?> <?php echo htmlspecialchars($user->nom) ?>">
            </div>
        <?php
        endforeach;
        ?>
    </div>
    <script src="script.js"></script>
    <?php
    }
    ?>
</body>
</html>