<?php
    session_start();
    require_once "../model/config.php";
    require_once "../functions/user.php";

    if($_SERVER["REQUEST_METHOD"] === "POST") {
      $email = trim($_POST["email"]);
      $password = trim($_POST["password"]);

      $result = loginUser($pdo, $email, $password);

      if ($result['success']) {
        $_SESSION['user_id'] = $result['user_id'];
        header("Location: dashboard.php");
        exit();
      } else {
        $error = $result['error'];
      }
    }
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Connexion</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="../../public/css/register.css">
  <link rel="stylesheet" href="../../public/css/global.css">
</head>
<body>
  <?php include "../includes/header_login_register.php" ?>

  <div class="form-container">
    <h2>Connexion</h2>
    <?php if (isset($error)): ?>
        <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>
    <form method="POST">
      <label for="email">Email</label>
      <input type="email" id="email" name="email" required>

      <label for="password">Mot de passe</label>
      <input type="password" id="password" name="password" required>

      <button type="submit">Se connecter</button>
      <p>Pas encore de compte ? <a href="register.php">Créer un compte</a></p>
    </form>
  </div>

  <footer>
    <div class="container">
      <p>&copy; <?php echo date("Y"); ?> - Application de gestion budgétaire. Tous droits réservés.</p>
    </div>
  </footer>
</body>
</html>