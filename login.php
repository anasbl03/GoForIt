<?php 
require("includes/db.php"); 
include("includes/header.php"); 

if ($_SERVER["REQUEST_METHOD"]=="POST"){
    $email=$_POST["email"];
    $password=$_POST["password"];

    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email]);

    $user = $stmt->fetch();

    if ($user) {
        if (password_verify($password, $user["password"])) {
            echo "Connexion réussie !";
        } else {
            echo "Mot de passe incorrect";
        }
    } else {
        echo "Utilisateur introuvable";
    }

}
?>
<div class="container mt-5 d-flex justify-content-center">

  <form method="POST" style="width: 400px;">

    <div>
        <label>Nom d'utilisateur</label><br>
        <input type="text" name="email" value="<?php echo isset($name) ? htmlspecialchars($username) : ''; ?>">
    </div>
    <div>
        <label>Mot de passe</label><br>
        <input type="password" name="password">
    </div>
    <div>
        <button type="submit">Se connecter</button>
    </div>
  </form>

</div>

<?php include("includes/footer.php"); ?>