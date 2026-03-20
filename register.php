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

            echo "<div class='alert alert-success'>Inscription réussie !</div>";
        }
    }
}
?>

<div class="container mt-5 d-flex justify-content-center">

  <form method="POST" style="width: 400px;">
    
    <div class="mb-3">
      <label class="form-label">Nom</label>
      <input type="text" class="form-control" name="name" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Email</label>
      <input type="email" class="form-control" name="email" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Mot de passe</label>
      <input type="password" class="form-control" name="password" required>
    </div>

    <div class="mb-3">
      <label>Confirmer mot de passe</label>
      <input type="password" class="form-control" name="passwordConfirm" required>
    </div>

    <button class="btn btn-primary w-100" type="submit">
      S'inscrire
    </button>

    <a href="login.php">Déjà un compte ?</a>

  </form>

</div>


<?php include("includes/footer.php"); ?>