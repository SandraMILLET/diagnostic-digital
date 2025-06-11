<?php
// Connexion à la base
$host = 'db5016452467.hosting-data.io';
$port = 3306;
$dbname = 'dbs13361244';
$username = 'dbu2883943';
$password = 'boNqax-ranqeb-cuvve8';


require_once './.env.php';

try {
    $pdo = new PDO('mysql:host=localhost;dbname=audit_express;charset=utf8', $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $email = isset($_GET['email']) ? $_GET['email'] : '';
    if ($email) {
        $stmt = $pdo->prepare("INSERT INTO click_tracking (email, clicked_at) VALUES (?, NOW())");
        $stmt->execute([$email]);
    }
} catch (Exception $e) {
    // erreur silencieuse
}

// 🔁 Redirection dynamique
$redirectUrl = isset($_GET['redirect']) ? urldecode($_GET['redirect']) : 'https://rsm-web-solutions.metaforma.io/';
header('Location: ' . $redirectUrl);
exit;

