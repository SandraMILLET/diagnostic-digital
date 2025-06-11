
<?php
// Affichage des erreurs pour le débogage
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Chargement de PHPMailer
require __DIR__ . '/src/PHPMailer.php';
require __DIR__ . '/src/SMTP.php';
require __DIR__ . '/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Connexion à la base
$host = 'db5016452467.hosting-data.io';
$port = 3306;
$dbname = 'dbs13361244';
$username = 'dbu2883943';
$password = 'boNqax-ranqeb-cuvve8';

try {
  $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8", $username, $password);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  http_response_code(500);
  echo "Erreur BDD: " . $e->getMessage();
  exit;
}

// Traitement des données
$data = json_decode(file_get_contents("php://input"), true);
if (!$data || !isset($data['email'], $data['score'], $data['niveau'], $data['responses'])) {
  http_response_code(400);
  echo "Requête invalide.";
  exit;
}

$email = $data['email'];
$website = $data['website'] ?? '';
$score = $data['score'];
$niveau = $data['niveau'];
$responses = $data['responses'];
$date = date('Y-m-d H:i:s');

// Enregistrement en BDD
try {
  $stmt = $pdo->prepare("INSERT INTO submissions (email, website, score, niveau, responses, submitted_at) VALUES (?, ?, ?, ?, ?, ?)");
  $stmt->execute([$email, $website, $score, $niveau, json_encode($responses), $date]);
} catch (PDOException $e) {
  http_response_code(500);
  echo "Erreur SQL : " . $e->getMessage();
  exit;
}

// Génération du contenu email
$questions = json_decode(file_get_contents('data_conseils.json'), true)['questions'];
$htmlFusionne = '';


foreach ($responses as $index => $val) {
  $q = $questions[$index];

  foreach ($q['answers'] as $answer) {
    if ((int)$answer['score'] === (int)$val) {
      $htmlFusionne .=
        "<div class='bloc-question'>" .
          "<p class='question'>" . ($index + 1) . ". " . $q['question'] . "</p>" .
          "<p class='answer'>✅ " . $answer['label'] . "</p>" .
          "<div class='conseil'>💡 " . $answer['conseil'] . "</div>" .
        "</div>";
      break;
    }
  }
}

//file_put_contents("debug.json", json_encode($responses));
//file_put_contents("debug_reponses.txt", $reponses_html);
//file_put_contents("debug_conseils.txt", $conseils_html);
// Vérification des données

// Chargement du template
$template = file_get_contents(__DIR__ . '/email_template_rsm.html');

if (!$template) {
  echo "❌ Le template n'a pas été chargé.";
  exit;
}
// Remplacement des variables dans le template
$template = str_replace('{{score}}', $score, $template);
$template = str_replace('{{niveau}}', $niveau, $template);
$template = str_replace('{{htmlFusionne}}', $htmlFusionne, $template);

$template = str_replace('{{email}}', $email, $template); // pour pixel tracking
//file_put_contents("debug_email_output.html", $template);

// Chargement du mot de passe depuis .env
$config = require __DIR__ . '/.env.php';
// Envoi du mail
$mail = new PHPMailer(true);
try {
  $mail->isSMTP();
  $mail->Host = 'smtp.ionos.fr';
  $mail->Port = 587;
  $mail->SMTPAuth = true;
  $mail->Username = 'contact@rsm-websolutions.fr';
  $mail->Password = $config['smtp_password'];

  $mail->SMTPSecure = 'tls';

  $mail->setFrom('contact@rsm-websolutions.fr', 'Sandra – RSM Web Solutions');
  $mail->addAddress($email);
  $mail->isHTML(true);
  $mail->CharSet = 'UTF-8';
  $mail->Encoding = 'base64';
  $mail->Subject = '📩 Ton plan digital personnalisé est prêt !';
$template = str_replace('{{tracking_pixel}}', '<img src="https://diagnostic-digital-express.rsm-websolutions.fr/track.php?email=' . urlencode($email) . '" width="1" height="1" style="display:none;" />', $template);
$template = str_replace('{{email}}', $email, $template);
$template = str_replace('{{prenom}}', $prenom, $template);

  //file_put_contents("debug_email_final.html", $template);
  $mail->Body = $template;

  $mail->send();
  echo 'OK';
} catch (Exception $e) {
  http_response_code(500);
  echo "Erreur d’envoi du mail : " . $mail->ErrorInfo;
}
?>
