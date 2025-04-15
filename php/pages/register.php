<?php
  ob_start();
  session_start();
  require_once "../model/config.php";

  if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nom = trim($_POST["nom"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $confirmPassword = $_POST["confirm-password"];

    $errors = [
      'nom' => [],
      'email' => [],
      'password' => [],
      'confirm-password' => [],
      'general' => []
    ];

    if (empty($nom)) {
      $errors['nom'][] = "Le nom est requis.";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || empty($email)) {
      $errors['email'][] = "L'email est invalide.";
    }
    if (strlen($password) < 8) {
      $errors['password'][] = "Le mot de passe doit contenir au moins 8 caractères.";
    }
    if ($password !== $confirmPassword) {
      $errors['confirm-password'][] = "Les mots de passe ne correspondent pas.";
    }

    // Check if email already exists
    try {
      $queryCheckEmail = "SELECT COUNT(*) FROM `users` WHERE `email` = :email";
      $stmtCheck = $pdo->prepare($queryCheckEmail);
      $stmtCheck->bindParam(':email', $email);
      $stmtCheck->execute();
      if ($stmtCheck->fetchColumn() > 0) {
        $errors['email'][] = "Cet email est déjà utilisé.";
      }
    } catch (PDOException $e) {
      $errors['email'][] = "Erreur lors de la vérification de l'email : " . $e->getMessage();
    }

    // Check if there are any errors
    $hasErrors = false;
    foreach ($errors as $fieldErrors) {
      if (!empty($fieldErrors)) {
        $hasErrors = true;
        break;
      }
    }

    if (!$hasErrors) {
      try {
          $hashedPassword = password_hash($password, PASSWORD_ARGON2ID);

          $queryRegister = "INSERT INTO `users` (`nom`, `email`, `password`) VALUES (:nom, :email, :password)";
          $stmt = $pdo->prepare($queryRegister);
          $stmt->bindParam(':nom', $nom);
          $stmt->bindParam(':email', $email);
          $stmt->bindParam(':password', $hashedPassword);

          if ($stmt->execute()) {
            header("Location: login.php");
            exit;
          } else {
            $errorInfo = $stmt->errorInfo();
            $errors['general'][] = "Erreur lors de l'inscription : " . $errorInfo[2];
          }
      } catch (PDOException $e) {
        $errors['general'][] = "Erreur de base de données : " . $e->getMessage();
      }
    }

    // Store errors for display
    $_SESSION['form_errors'] = $errors;
  }
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Inscription</title>
  <link rel="stylesheet" href="../../css/register.css">
  <link rel="stylesheet" href="../../css/global.css">
  <style>
    .error {
      color: red;
      font-size: 0.9em;
      margin-top: 0.2em;
    }
  </style>
</head>
<body>
  <header>
    <div class="container">
    <svg style="width: 60px; cursor: pointer;" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
      <circle cx="50" cy="50" r="45" fill="#f0f4f8" stroke="#2c5282" stroke-width="2"/>
      <path d="M55,40 h-15 M55,50 h-18 M55,60 h-15" stroke="#2c5282" stroke-width="3" stroke-linecap="round"/>
      <path d="M40,35 v30" stroke="#2c5282" stroke-width="3" stroke-linecap="round"/>
      <polyline points="55,55 65,45 75,50 85,35" fill="none" stroke="#4299e1" stroke-width="2.5" stroke-linejoin="round"/>
      <text x="50" y="75" font-family="Arial, sans-serif" font-size="12" font-weight="bold" text-anchor="middle" fill="#2c5282">GB</text>
    </svg>
      <nav>
        <a href="index.html">Accueil</a>
        <a href="login.html">Connexion</a>
        <a href="register.html">Inscription</a>
      </nav>
    </div>
  </header>

  <div class="form-container">
    <h2>Créer un compte</h2>
    <form method="POST">
      <label for="nom">Nom</label>
      <input type="text" id="nom" name="nom" required>
      <?php if (isset($_SESSION['form_errors']['nom']) && !empty($_SESSION['form_errors']['nom'])): ?>
        <?php foreach ($_SESSION['form_errors']['nom'] as $error): ?>
          <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php endforeach; ?>
      <?php endif; ?>

      <label for="email">Email</label>
      <input type="email" id="email" name="email" required>
      <?php if (isset($_SESSION['form_errors']['email']) && !empty($_SESSION['form_errors']['email'])): ?>
        <?php foreach ($_SESSION['form_errors']['email'] as $error): ?>
          <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php endforeach; ?>
      <?php endif; ?>

      <label for="password">Mot de passe</label>
      <input type="password" id="password" name="password" required>
      <?php if (isset($_SESSION['form_errors']['password']) && !empty($_SESSION['form_errors']['password'])): ?>
        <?php foreach ($_SESSION['form_errors']['password'] as $error): ?>
          <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php endforeach; ?>
      <?php endif; ?>

      <label for="confirm-password">Confirmer le mot de passe</label>
      <input type="password" id="confirm-password" name="confirm-password" required>
      <?php if (isset($_SESSION['form_errors']['confirm-password']) && !empty($_SESSION['form_errors']['confirm-password'])): ?>
        <?php foreach ($_SESSION['form_errors']['confirm-password'] as $error): ?>
          <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php endforeach; ?>
      <?php endif; ?>

      <?php if (isset($_SESSION['form_errors']['general']) && !empty($_SESSION['form_errors']['general'])): ?>
        <div class="general-errors">
          <?php foreach ($_SESSION['form_errors']['general'] as $error): ?>
            <p class="error"><?php echo htmlspecialchars($error); ?></p>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

      <button type="submit">S'inscrire</button>
      <p>Déjà un compte ? <a href="login.php">Se connecter</a></p>
    </form>
  </div>

  <footer>
    <div class="container">
      <p>&copy; <?php echo date("Y"); ?> - Application de gestion budgétaire. Tous droits réservés.</p>
    </div>
  </footer>

  <?php
    // Clear session data after rendering
    unset($_SESSION['form_errors']);
  ?>
</body>
</html>
