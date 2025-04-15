<?php
    session_start();
    require_once "../model/config.php";

    if($_SERVER["REQUEST_METHOD"] === "POST") {
        $email = trim($_POST["email"]);
        $password = trim($_POST["password"]);

        try {
            $queryLogin = "SELECT id, email, password FROM users WHERE email = :email";
            $stmt = $pdo->prepare($queryLogin);
            $stmt->bindParam(":email", $email);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['id'] = $user['id'];
                header("Location: dashboard.php");
                exit;
            } else {
                $error = "Email ou mot de passe incorrect";
            }
        } 
        catch (PDOException $e) {
            $error = "Erreur de connexion: " . $e->getMessage();
        }
    }

    var_dump($_SESSION);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Connexion</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="../../css/register.css">
  <link rel="stylesheet" href="../../css/global.css">
</head>
<body>
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
</body>
</html>