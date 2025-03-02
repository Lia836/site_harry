<?php
// Définition des constantes pour améliorer la maintenabilité du code
define('JSON_FILE', 'users.json');
define('IMAGE_DIR', 'images/');

/**
 * Fonction pour gérer les erreurs de manière centralisée
 * 
 * @param string $message Message d'erreur à logger
 * @return string Message d'erreur formaté en HTML
 */
function handleError($message) {
    error_log($message); // Enregistre l'erreur dans les logs du serveur
    return "<p>Une erreur est survenue. Veuillez réessayer plus tard.</p>";
}

// Vérification de l'existence et de la lisibilité du fichier JSON
if (!is_readable(JSON_FILE)) {
    echo handleError("Le fichier JSON n'est pas accessible.");
    exit; // Arrête l'exécution du script en cas d'erreur
}

// Lecture et décodage du contenu JSON
$jsonContent = file_get_contents(JSON_FILE);
$data = json_decode($jsonContent);

// Vérification des erreurs de décodage JSON
if (json_last_error() !== JSON_ERROR_NONE) {
    echo handleError("Erreur lors du décodage JSON : " . json_last_error_msg());
    exit;
}

// Extraction des données de personnes et de localisations
$users = $data->personnes ?? []; // Utilisation de l'opérateur de fusion null
$localisations = $data->localisations ?? [];

// Ajout des nouveaux lieux (Egypte et Roumanie)
$localisations->Egypte = (object)["text" => "en Égypte", "img" => "egypte.png"];
$localisations->Roumanie = (object)["text" => "en Roumanie", "img" => "roumanie.png"];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Écrire horloge</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="form-container">
        <h1>Je suis bien arrivé dans ce lieu</h1>
        <form method="get" action="traitement_ecrire_horloge.php">
            <div class="form-group">
                <label for="user">Je suis : </label>
                <select name="user" id="user" required>
                    <option value="" selected disabled>-- Qui êtes-vous ? --</option>
                    <?php foreach ($users as $key => $user): ?>
                        <option value="<?= htmlspecialchars($key) ?>"><?= htmlspecialchars($user->prenom . ' ' . $user->nom) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <fieldset>
                    <legend>Choisissez votre lieu :</legend>
                    <?php 
                    // Boucle sur les localisations pour afficher les options de lieu
                    foreach ($localisations as $key => $loc): 
                        // Assure que l'extension de l'image est .png
                        $imgPath = IMAGE_DIR . pathinfo($loc->img, PATHINFO_FILENAME) . '.png';
                    ?>
                        <label>
                            <input type="radio" name="lieu" value="<?= htmlspecialchars($key) ?>" required />
                            <?= htmlspecialchars($key) ?>
                            <?php if (file_exists($imgPath)): ?>
                                <img src="<?= $imgPath ?>" alt="<?= htmlspecialchars($key) ?>" width="20" height="20">
                            <?php endif; ?>
                        </label>
                    <?php endforeach; ?>
                </fieldset>
            </div>
            <input type="submit" value="Envoyer mon lieu" />
        </form>
    </div>
    <script src="script.js"></script>
</body>
</html>

