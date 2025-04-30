<?php
  ob_start();
  session_start();
  require_once "../model/config.php";
  require_once "../functions/user.php";

  if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nom = trim($_POST["nom"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $confirmPassword = $_POST["confirm-password"];

    $errors = [];

    if (empty($nom)) {
      $errors['nom'] = "Le nom est requis.";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || empty($email)) {
      $errors['email'] = "L'email est invalide.";
    }
    if (strlen($password) < 8) {
      $errors['password'] = "Le mot de passe doit contenir au moins 8 caractères.";
    }
    if ($password !== $confirmPassword) {
      $errors['confirm-password'] = "Les mots de passe ne correspondent pas.";
    }

    $emailAvailable = checkEmailExists($pdo, $email, $errors);

    $hasErrors = false;
    foreach ($errors as $fieldErrors) {
      if (!empty($fieldErrors)) {
        $hasErrors = true;
        break;
      }
    }

    if (!$hasErrors) {
      if ($emailAvailable && empty($errors)) {
        $result = registerUser($pdo, $nom, $email, $password, $errors);
        if ($result['success']) {
          $success = true;
          $_SESSION['user_id'] = $result['user_id'];
          header("Location: login.php");
          exit();
        }
        else {
          $errors = $result['error'];
        }
      }
    }

    $_SESSION['form_errors'] = $errors;
  }
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Inscription</title>
  <link rel="stylesheet" href="../../public/css/register.css">
  <link rel="stylesheet" href="../../public/css/global.css">
  <style>
    .error {
      color: red;
      font-size: 0.9em;
      margin-top: 0.2em;
    }
  </style>
</head>
<body>
  <?php include "../includes/header_login_register.php" ?>

  <div class="form-container">
    <h2>Créer un compte</h2>
    <form method="POST">
      <label for="nom">Nom</label>
      <input type="text" id="nom" name="nom" value="<?php echo htmlspecialchars($nom ?? ''); ?>" required>
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
    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
      unset($_SESSION['form_errors']);
    }
  ?>
</body>
</html>
