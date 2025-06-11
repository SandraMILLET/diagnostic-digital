
<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

$host = 'db5016452467.hosting-data.io';
$port = 3306;
$dbname = 'dbs13361244';
$username = 'dbu2883943';
$password = 'boNqax-ranqeb-cuvve8';

try {
  $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8", $username, $password);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  die("Erreur de connexion : " . $e->getMessage());
}

// Si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $ids = $_POST['metaforma'] ?? [];
  $pdo->exec("UPDATE submissions SET metaforma_done = 0");
  if (!empty($ids)) {
    $placeholders = implode(',', array_map('intval', $ids));
    $pdo->exec("UPDATE submissions SET metaforma_done = 1 WHERE id IN ($placeholders)");
  }
}

// Récupérer les soumissions
$stmt = $pdo->query("
  SELECT s.*, 
         (SELECT COUNT(*) FROM open_tracking o WHERE o.email = s.email) AS opened,
         (SELECT COUNT(*) FROM click_tracking c WHERE c.email = s.email) AS clicked
  FROM submissions s
  ORDER BY submitted_at DESC
");
$submissions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <title>Dashboard – Diagnostic Digital</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&family=Poppins&display=swap" rel="stylesheet" />
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(263deg, #412e7e, #a6c2f4, #6e84de, #b687e2, #9b5dc9);
    }
    h1 {
      font-family: 'Montserrat', sans-serif;
    }
    table {
      background-color: white;
    }
  </style>
</head>
<body class="p-8">
  <h1 class="text-3xl font-bold text-white mb-6">📊 Dashboard – Résultats du Diagnostic</h1>

  <form method="POST" class="bg-white rounded-xl shadow-lg p-4 overflow-x-auto">
    <table class="min-w-full text-sm text-gray-700">
      <thead class="bg-[#6e84de] text-white">
        <tr>
          <th class="px-4 py-2">Date</th>
          <th class="px-4 py-2">Email</th>
          <th class="px-4 py-2">Site</th>
          <th class="px-4 py-2">Score</th>
          <th class="px-4 py-2">Niveau</th>
          <th class="px-4 py-2">Réponses</th>
          <th class="px-4 py-2">📬 Ouvert</th>
          <th class="px-4 py-2">🔗 Cliqué</th>
          <th class="px-4 py-2">✅ Metaforma</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($submissions as $row): ?>
        <tr class="border-t">
          <td class="px-4 py-2"><?= htmlspecialchars($row['submitted_at']) ?></td>
          <td class="px-4 py-2"><?= htmlspecialchars($row['email']) ?></td>
          <td class="px-4 py-2"><?= htmlspecialchars($row['website']) ?></td>
          <td class="px-4 py-2"><?= (int)$row['score'] ?> / 30</td>
          <td class="px-4 py-2"><?= htmlspecialchars($row['niveau']) ?></td>
          <td class="px-4 py-2 text-xs break-words"><?= htmlspecialchars($row['responses']) ?></td>
          <td class="px-4 py-2 text-center"><?= $row['opened'] > 0 ? '✅' : '❌' ?></td>
          <td class="px-4 py-2 text-center"><?= $row['clicked'] > 0 ? '✅' : '❌' ?></td>
          <td class="px-4 py-2 text-center">
            <input type="checkbox" name="metaforma[]" value="<?= $row['id'] ?>" <?= $row['metaforma_done'] ? 'checked' : '' ?>>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <div class="text-right mt-4">
      <button type="submit" class="bg-[#412e7e] text-white py-2 px-6 rounded-lg hover:bg-[#6e84de] font-semibold transition">
        💾 Enregistrer les changements
      </button>
    </div>
  </form>
</body>
</html>
