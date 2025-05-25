
<?php
$host = '127.0.0.1';
$port = 8889;
$dbname = 'audit_express';
$username = 'root';
$password = 'root';

try {
  $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8", $username, $password);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  exit;
}

$email = $_GET['email'] ?? null;
if ($email) {
  try {
    $stmt = $pdo->prepare("INSERT INTO open_tracking (email) VALUES (?)");
    $stmt->execute([$email]);
  } catch (PDOException $e) {
    // Erreur silencieuse
  }
}

// Envoyer une image gif transparente 1x1
header('Content-Type: image/gif');
readfile(__DIR__ . '/pixel.gif');
?>
