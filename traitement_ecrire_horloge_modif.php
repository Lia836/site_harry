<?php
// Définition des constantes
define('JSON_FILE', 'users.json');
define('DATE_FORMAT', DateTime::ATOM);
define('IMAGE_DIR', 'images/');

/**
 * Fonction pour gérer les erreurs
 * @param string $message Message d'erreur
 * @param bool $logError Indique si l'erreur doit être enregistrée
 * @return string Message d'erreur formaté en HTML
 */
function handleError($message, $logError = true) {
    if ($logError) {
        error_log($message);
    }
    return "<p class='error'>Une erreur est survenue. Veuillez réessayer plus tard.</p>";
}

// Récupération et assainissement des paramètres GET
$lieu = filter_input(INPUT_GET, "lieu", FILTER_SANITIZE_STRING);
$id = filter_input(INPUT_GET, "user", FILTER_SANITIZE_STRING);

// Vérification de l'existence du fichier et de la validité des paramètres
if (is_file(JSON_FILE) && $lieu !== false && $id !== false) {
    // Lecture et décodage du contenu JSON
    $jsonContent = file_get_contents(JSON_FILE);
    if ($jsonContent === false) {
        die(handleError("Erreur lors de la lecture du fichier JSON."));
    }
    
    $users = json_decode($jsonContent);
    if ($users === null) {
        die(handleError("Erreur lors du décodage JSON: " . json_last_error_msg()));
    }
    
    // Ajout des nouveaux lieux s'ils n'existent pas déjà
    if (!isset($users->localisations->Egypte)) {
        $users->localisations->Egypte = (object)["text" => "en Égypte", "img" => "egypte.png"];
    }
    if (!isset($users->localisations->Roumanie)) {
        $users->localisations->Roumanie = (object)["text" => "en Roumanie", "img" => "roumanie.png"];
    }
    
    // Vérification de l'existence de l'utilisateur spécifié
    if (isset($users->personnes->$id)) {
        // Mise à jour du lieu et de la date pour l'utilisateur
        $users->personnes->$id->lieu = $lieu;
        $users->personnes->$id->date = (new DateTime())->format(DATE_FORMAT);
        
        // Uniformisation du format d'image en PNG
        foreach ($users->localisations as $key => $loc) {
            $users->localisations->$key->img = pathinfo($loc->img, PATHINFO_FILENAME) . '.png';
        }
        
        // Encodage et écriture du JSON mis à jour
        $jsonEncoded = json_encode($users, JSON_PRETTY_PRINT);
        if ($jsonEncoded === false) {
            die(handleError("Erreur lors de l'encodage JSON: " . json_last_error_msg()));
        }
        
        if (file_put_contents(JSON_FILE, $jsonEncoded) === false) {
            die(handleError("Erreur lors de l'écriture du fichier JSON."));
        }
        
        // Génération du message de résultat
        $l = $users->personnes->$id->lieu;
        $message = isset($users->localisations->$l->text) 
            ? "<p class='success'>" . htmlspecialchars($users->personnes->$id->prenom) . " " . 
              htmlspecialchars($users->personnes->$id->nom) . " est " . 
              htmlspecialchars($users->localisations->$l->text) . ".</p>"
            : "<p class='warning'>Localisation non trouvée.</p>";
    } else {
        $message = "<p class='warning'>Utilisateur non trouvé.</p>";
    }
} else {
    $message = "<p class='error'>Fichier non trouvé ou paramètres invalides.</p>";
}

// En-têtes pour la gestion du cache
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Traitement - Écrire horloge</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Résultat du traitement</h1>
        <?php echo $message; ?>
        <a href="ecrire_horloge.php" class="button">Retour</a>
    </div>
    <script src="script.js"></script>
</body>
</html>
