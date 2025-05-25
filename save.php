
<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Connexion à la base
$host = '127.0.0.1';
$port = 8889;
$dbname = 'audit_express';
$username = 'root';
$password = 'root';

try {
  $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8", $username, $password);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  http_response_code(500);
  echo "Erreur BDD : " . $e->getMessage();
  exit;
}

// Lecture des données envoyées
$data = json_decode(file_get_contents("php://input"), true);

if (!$data || !isset($data['email'], $data['score'], $data['niveau'], $data['responses'])) {
  http_response_code(400);
  echo "Requête incomplète ou mal formatée.";
  exit;
}

$email = $data['email'];
$website = $data['website'] ?? '';
$score = $data['score'];
$niveau = $data['niveau'];
$responses = $data['responses'];

try {
  $stmt = $pdo->prepare("INSERT INTO submissions (email, website, score, niveau, responses) VALUES (?, ?, ?, ?, ?)");
  $stmt->execute([$email, $website, $score, $niveau, json_encode($responses)]);
  echo "✅ Données enregistrées avec succès.";
} catch (PDOException $e) {
  http_response_code(500);
  echo "Erreur SQL : " . $e->getMessage();
}
?>
