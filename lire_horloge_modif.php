<?php
// Définition des constantes pour le fichier JSON et le dossier des images
define('JSON_FILE', 'users.json');
define('IMAGE_DIR', 'images/');

// Fonction pour gérer les erreurs
function handleError($message) {
    error_log($message); // Enregistrer l'erreur dans les logs du serveur
    return "<p>Une erreur est survenue. Veuillez réessayer plus tard.</p>";
}

// Vérification de l'existence et de la lisibilité du fichier JSON
if (!is_readable(JSON_FILE)) {
    echo handleError("Le fichier JSON n'est pas accessible.");
    exit;
}

// Lecture et décodage du contenu JSON avec gestion des erreurs
$jsonContent = file_get_contents(JSON_FILE);
$data = json_decode($jsonContent);

if (json_last_error() !== JSON_ERROR_NONE) {
    echo handleError("Erreur lors du décodage JSON : " . json_last_error_msg());
    exit;
}

// Extraction des données de personnes et de localisations
$personnes = $data->personnes ?? [];
$localisations = $data->localisations ?? [];

// Obtention de l'heure actuelle du serveur en millisecondes pour une synchronisation avec JavaScript
$serverTime = time() * 1000;

// Vérification de la date de dernière modification du fichier JSON pour optimiser les requêtes HTTP (caching)
$lastModified = filemtime(JSON_FILE);
$etag = md5_file(JSON_FILE);

header("Last-Modified: " . gmdate("D, d M Y H:i:s", $lastModified) . " GMT");
header("Etag: $etag");

// Début du HTML
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lire l'horloge</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- Affichage de l'heure numérique -->
    <div id="digital-time"></div>

    <!-- Conteneur principal de l'horloge -->
    <div class="clock">
        <!-- Boucle pour afficher les localisations -->
        <?php foreach ($localisations as $nom => $coords): ?>
            <div class="location" id="<?= htmlspecialchars($nom) ?>" data-angle="0">
                <?php
                // Modification ici : on force l'extension .png
                $imgPath = IMAGE_DIR . pathinfo($coords->img, PATHINFO_FILENAME) . '.png';
                if (file_exists($imgPath)): ?>
                    <img src="<?= $imgPath ?>" alt="<?= htmlspecialchars($nom) ?>">
                <?php else: ?>
                    <span>Image non disponible</span>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>

        <!-- Boucle pour afficher les aiguilles associées aux utilisateurs -->
        <?php foreach ($personnes as $key => $user): ?>
            <div class="aiguille <?= htmlspecialchars($user->lieu) ?>" data-lieu="<?= htmlspecialchars($user->lieu) ?>">
                <?php
                // Modification ici : on force l'extension .png
                $imgPath = IMAGE_DIR . pathinfo($user->img, PATHINFO_FILENAME) . '.png';
                if (file_exists($imgPath)): ?>
                    <img src="<?= $imgPath ?>" alt="<?= htmlspecialchars($user->prenom) ?> <?= htmlspecialchars($user->nom) ?>">
                <?php else: ?>
                    <span><?= htmlspecialchars($user->prenom) ?> <?= htmlspecialchars($user->nom) ?></span>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Passage de l'heure du serveur au JavaScript -->
    <script>
        var serverTime = <?= $serverTime ?>;
    </script>
    <script src="script.js"></script>
</body>
</html>



