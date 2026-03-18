<?php include("includes/header.php"); ?>

<?php
require("includes/db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $username = $_POST["username"];


    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);


    $sql = "INSERT INTO users (name, email, password, username) VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$name, $email, $hashedPassword, $username]);

    echo "<div class='alert alert-success'>Inscription réussie !</div>";
}
?>

<form method="POST" action="">
    <form class="row g-3 needs-validation" novalidate>
        <div class="col-md-4">
            <label for="validationCustom01" class="form-label">Nom</label>
            <input type="text" class="form-control" id="validationCustom01" name="name" required>
            <div class="valid-feedback">Looks good!</div>
        </div>

  <div class="col-md-4">
    <label for="validationCustom02" class="form-label">Email</label>
    <input type="text" class="form-control" id="validationCustom02"  name="email" required>
    <div class="valid-feedback">
      Looks good!
    </div>
  </div>

  <div class="col-md-4">
    <label for="validationCustomUsername" class="form-label">Username</label>
    <div class="input-group has-validation">
      <input type="text" class="form-control" id="validationCustomUsername" aria-describedby="inputGroupPrepend" name="username" required>
      <div class="invalid-feedback">
        Please choose a username.
      </div>
    </div>
  </div>

  <div class="col-md-6">
    <label for="validationCustom03" class="form-label">Mot de passe</label>
    <input type="text" class="form-control" id="validationCustom03" name="password" required>
    <div class="invalid-feedback">
      Entrez un mot de passe valide.
    </div>
  </div>

 
  <div class="col-12">
    <div class="form-check">
      <input class="form-check-input" type="checkbox" value="" id="invalidCheck" required>
      <label class="form-check-label" for="invalidCheck">
        Agree to terms and conditions
      </label>
      <div class="invalid-feedback">
        You must agree before submitting.
      </div>
    </div>
  </div>

  <div class="col-12">
    <button class="btn btn-primary" type="submit">S'inscrire</button>
  </div>
    </form>
</form>


<?php include("includes/footer.php"); ?>