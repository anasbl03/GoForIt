<?php
require("includes/db.php");
include("includes/header.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $passwordConfirm= $_POST["passwordConfirm"];

    if (empty($name)|| empty($email)|| empty($password)||empty($passwordConfirm)){
      echo "<div class='alert alert-danger'>Veuillez remplir tous les champs</div>";
    } elseif ($password !== $passwordConfirm) {
      echo "<div class='alert alert-danger'>Les mots de passe ne correspondent pas</div>";
    } elseif (strlen($password) < 6){
      echo "<div class='alert alert-danger'> Le mot de passe doit contenir au moins 6 caractères.</div>";
    } elseif (!preg_match('/[A-Z]/', $password)) {
      echo "<div class='alert alert-danger'> Le mot de passe doit contenir au moins une majuscule.</div>";
    } elseif (!preg_match('/[a-z]/', $password)) {
      echo "<div class='alert alert-danger'> Le mot de passe doit contenir au moins une minuscule.</div>";
    } elseif (!preg_match('/[0-9]/', $password)) {
      echo "<div class='alert alert-danger'> Le mot de passe doit contenir au moins un chiffre.</div>";
    }else {
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$email]);

        if ($stmt->rowCount() > 0) {
            echo "<div class='alert alert-danger'>Email déjà utilisé</div>";        
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$name, $email, $hashedPassword]);

            echo "<div class='alert alert-success'>Inscription réussie ! Redirection dans 2 secondes...</div>";
            echo "<meta http-equiv='refresh' content='2;url=login.php'>";
        }
    }
}
?>

<div class="container-fluid vh-100 d-flex align-items-center justify-content-center bg-light">

  <div class="card shadow-lg p-4" style="width: 420px; border-radius: 15px;">
    
    <div class="text-center mb-4">
      <h3 class="fw-bold">Créer un compte</h3>
      <p class="text-muted">Inscris-toi pour commencer</p>
    </div>

    <form method="POST">

      <div class="form-floating mb-3">
        <input type="text" class="form-control" name="name" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" placeholder="Nom" required>
        <label>Nom</label>
      </div>

      <div class="form-floating mb-3">
        <input type="email" class="form-control" name="email" placeholder="Email" required>
        <label>Email</label>
      </div>

      <div class="form-floating mb-3">
        <input type="password" class="form-control" name="password" placeholder="Mot de passe" required>
        <label>Mot de passe</label>
      </div>

      <div class="form-floating mb-3">
        <input type="password" class="form-control" name="passwordConfirm" placeholder="Confirmer" required>
        <label>Confirmer mot de passe</label>
      </div>

      <button class="btn btn-primary w-100 py-2 mb-3" type="submit">
        S'inscrire
      </button>

      <div class="text-center">
        <a href="login.php" class="text-decoration-none">
          Déjà un compte ? Se connecter
        </a>
      </div>

    </form>

  </div>

</div>


<?php include("includes/footer.php"); ?>