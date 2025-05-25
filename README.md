
# 🔍 Diagnostic Digital Express – RSM Web Solutions

Bienvenue dans le projet **Diagnostic Digital Express**, une mini-application web développée pour offrir aux indépendants et TPE un aperçu rapide de leur maturité digitale, avec des conseils personnalisés.

---

## ✨ Fonctionnalités principales

- Questionnaire dynamique de 10 questions avec notation
- Affichage d'une **progress bar animée**
- Affichage immédiat des résultats
- Génération de **conseils personnalisés sous chaque réponse**
- Envoi du bilan complet par email (via MailHog ou SMTP réel)
- Stockage des résultats dans une base de données MySQL
- **Dashboard admin stylisé** pour consulter les résultats
- Pixel de tracking invisible (pour suivi d'ouverture)
- Tunnel de vente intégré (vers checklist SEO Express)

---

## 🧱 Structure des fichiers

```
📁 diagnostic_complet_fonctionnel_copie/
├── index.html                  # Questionnaire utilisateur
├── app.js                     # Logique du questionnaire
├── data_conseils.json         # Données des questions/réponses/conseils
├── merci.html                 # Page de remerciement stylisée
├── email_template_rsm.html    # Modèle HTML du mail stylisé RSM
├── save.php                   # Enregistrement + envoi email
├── admin.php                  # Tableau de bord des soumissions
├── track.php                  # Pixel de suivi email
├── logo.png                   # Logo RSM Web Solutions
└── src/                       # PHPMailer
```

---

## ⚙️ Configuration locale

### 📬 Tester les emails avec MailHog

1. Lance MailHog :
   ```
   mailhog
   ```

2. Accède à : [http://localhost:8025](http://localhost:8025)

3. Tous les mails envoyés par `save.php` s’affichent ici automatiquement.

---

## 📨 Personnaliser l'envoi d'email

Dans `save.php` :

- Pour tester en local :
```php
$mail->Host = 'localhost';
$mail->Port = 1025;
$mail->SMTPAuth = false;
```

- Pour passer en **prod réelle (SMTP Gmail, Brevo, etc.)**, remplace les lignes ci-dessus par ta config SMTP.

---

## 🧠 Données enregistrées

Chaque soumission stocke :

- Email de l’utilisateur
- URL de son site (optionnel)
- Score global / niveau
- Réponses aux 10 questions
- Date
- Statut : ouvert / cliqué / passé au tunnel

---

## 🛠️ Technologies utilisées

- HTML5 / TailwindCSS
- JavaScript vanilla
- PHP + PHPMailer
- MySQL / PDO
- MailHog (dev) / SMTP réel (prod)

---

## 🔐 Admin

Connecte-toi à `/admin.php` pour voir les soumissions.  
Depuis le dashboard, tu peux :

- Voir tous les résultats enregistrés
- Marquer si l’utilisateur est allé dans le tunnel
- Suivre les clics et ouvertures via les pixels

---

## 📩 Personnalisation

Le fichier `email_template_rsm.html` utilise les placeholders suivants :

- `{{score}}` – score global
- `{{niveau}}` – niveau du profil
- `{{htmlFusionne}}` – bloc complet question + réponse + conseil
- `{{email}}` – utilisé pour le pixel invisible

---

## 🖌️ Design RSM intégré

- Logo RSM affiché dans tous les écrans (et mail)
- Couleurs violettes et bleues : `#412e7e`, `#9b5dc9`, `#6e84de`, `#a6c2f4`, `#b687e2`
- Emails responsives & stylés

---

## 💬 Support

Contact : [Sandra – RSM Web Solutions](mailto:contact@rsm-websolutions.fr)

