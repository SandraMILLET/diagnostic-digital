<?php
$host = 'db5016452467.hosting-data.io';
$port = 3306;
$dbname = 'dbs13361244';
$username = 'dbu2883943';
$password = 'boNqax-ranqeb-cuvve8';

// Connexion à la base de données
// Assurez-vous que le fichier .env.php existe et contient les bonnes informations
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $email = isset($_GET['email']) ? $_GET['email'] : '';

    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $stmt = $pdo->prepare("INSERT INTO open_tracking (email, opened_at) VALUES (?, NOW())");
        $stmt->execute([$email]);

        // Log temporaire
        file_put_contents("track-debug.log", date("Y-m-d H:i:s") . " | $email\n", FILE_APPEND);
    }

    // Envoyer un pixel transparent 1x1
    header('Content-Type: image/gif');
    readfile('pixel.gif');

} catch (Exception $e) {
    file_put_contents("track-error.log", $e->getMessage(), FILE_APPEND);
    // même dans l'erreur on renvoie une image vide pour éviter le blocage
    header('Content-Type: image/gif');
    readfile('pixel.gif');
}
exit;