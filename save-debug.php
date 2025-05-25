
<?php
echo "<pre>Current path: " . __DIR__ . "</pre>";

ini_set('display_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/src/PHPMailer.php';
require __DIR__ . '/src/SMTP.php';
require __DIR__ . '/src/Exception.php';


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$data = json_decode(file_get_contents("php://input"), true);

if (!$data || !isset($data['email'])) {
  http_response_code(400);
  echo "❌ Donnée email manquante";
  exit;
}

$email = $data['email'];

$mail = new PHPMailer(true);

try {
  $mail->isSMTP();
  $mail->Host = 'localhost';
  $mail->Port = 1025;
  $mail->SMTPAuth = false;

  $mail->setFrom('contact@rsm-websolutions.fr', 'Sandra – RSM Web Solutions');
  $mail->addAddress($email);
  $mail->isHTML(true);
  $mail->Subject = '📩 Test d’envoi PHPMailer';
  $mail->Body = '<p>Voici un test d’envoi d’email depuis <strong>PHPMailer</strong>.</p>';

  $mail->send();
  echo "✅ Email envoyé à {$email}";
} catch (Exception $e) {
  http_response_code(500);
  echo "❌ Erreur d’envoi du mail : " . $mail->ErrorInfo;
}
?>
